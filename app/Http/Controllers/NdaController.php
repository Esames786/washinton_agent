<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NdaController extends Controller
{
    /**
     * The NDA on its own page. Someone who has already signed has no business here, so
     * they go straight back to the portal rather than being shown a form again.
     */
    public function page()
    {
        $user = Auth::user();
        if (! $user || empty($user->nda_required)) {
            return redirect('/dashboard');
        }

        return view('nda.page', ['signRoute' => route('nda.sign')]);
    }

    public function sign(Request $request): JsonResponse
    {
        $request->validate([
            'employee_name'  => 'required|string|max:255',
            'father_name'    => 'nullable|string|max:255',   // #7: optional
            'address'        => 'required|string|max:500',
            'cnic'           => 'required|string|max:20',
            'signature_data' => 'required|string',
            'agreed'         => 'required|in:1',
            'cnic_front'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'cnic_back'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();
        if (!$user || !$user->nda_required) {
            return response()->json(['success' => false, 'message' => 'NDA not required for this account.'], 403);
        }

        // Validate signature canvas data
        $sigBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $request->signature_data);
        $sigBinary = base64_decode($sigBase64);
        if (!$sigBinary || strlen($sigBinary) < 100) {
            return response()->json(['success' => false, 'message' => 'Invalid signature. Please draw again.']);
        }

        $signedAt = now();
        $ip       = $request->ip();
        $hrEmp    = DB::table('hr_employees')->where('agent_id', $user->id)->first();

        // The NDA copy the agent is signing = the admin's prepared copy, or the branded default.
        // Either way it is re-branded for THIS agent, so a Hello agent can never sign a copy
        // that names CrazyRays (e.g. saved while the CrazyRays portal brand was forced).
        $agentBrand = \App\Support\Brand::for($user);
        $ndaContent = $hrEmp->nda_content ?? null;
        if (!$ndaContent || trim(strip_tags($ndaContent)) === '') {
            $tpl        = \App\NdaTemplate::getDefault();
            $ndaContent = $tpl ? $tpl->content : '';
        }
        $ndaContent = $ndaContent ? \App\Support\Brand::applyTokens($ndaContent, $agentBrand) : '';

        // Store CNIC front/back if provided (public/Uploads so both apps serve it without a symlink).
        $cnicFrontPath = $this->storeUpload($request, 'cnic_front', $user->id, $signedAt);
        $cnicBackPath  = $this->storeUpload($request, 'cnic_back', $user->id, $signedAt);

        // Mirror any newly-captured ID image into hr_employee_documents so it also shows in the
        // normal documents list. #6: Hello agents upload a STATE ID (setting 21, up to 2 files);
        // CrazyRays staff a CNIC (#10 front / #11 back). Non-blocking.
        if ($hrEmp) {
            if (($agentBrand['key'] ?? '') !== 'crazyrays') {
                $this->mirrorCnicDoc($hrEmp->id, $user->id, 21, $cnicFrontPath, $signedAt, true);
                $this->mirrorCnicDoc($hrEmp->id, $user->id, 21, $cnicBackPath, $signedAt, true);
            } else {
                $this->mirrorCnicDoc($hrEmp->id, $user->id, 10, $cnicFrontPath, $signedAt);
                $this->mirrorCnicDoc($hrEmp->id, $user->id, 11, $cnicBackPath, $signedAt);
            }
        }

        // Persist the signed state BEFORE building the PDF.
        //
        // Reported 22 Sep 2026: an agent signed, the page hung, and nothing at all was saved — he
        // had to start again. The PDF used to be generated first, and DomPDF's first run on a
        // server builds its font cache: measured at 5.9s cold against 1.6s warm here, and far
        // worse on a loaded shared host. If PHP hit max_execution_time during that, the request
        // died before a single row was written, so a genuinely completed signature was lost.
        //
        // The signature, the NDA text and the IP are all the record actually needs — download()
        // already regenerates the PDF from exactly those when the file is missing. So the legal
        // record is committed first and the PDF becomes a best-effort cache of it.
        try {
            DB::table('user')->where('id', $user->id)->update([
                'nda_required'      => 0,
                'nda_signed_at'     => $signedAt,
                'nda_document_path' => null,
            ]);

            DB::table('hr_employees')->where('agent_id', $user->id)->update([
                'nda_required'     => 0,
                'nda_signed_at'    => $signedAt,
                'nda_document_url' => null,
                'nda_content'      => $ndaContent,
                'nda_signature'    => $request->signature_data,
                'nda_signed_ip'    => $ip,
                'nda_cnic_front'   => $cnicFrontPath,
                'nda_cnic_back'    => $cnicBackPath,
                'nda_father_name'  => $request->father_name,
                'nda_address'      => $request->address,
            ]);
        } catch (\Throwable $e) {
            Log::error('NDA flag clear failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Server error saving signature. Please try again.'], 500);
        }

        // The signature is safely recorded from here on, so the PDF is best-effort: if it fails or
        // the worker is killed mid-render, the agent stays signed and download() rebuilds the file
        // on demand. They never have to sign a second time because of a slow PDF.
        try {
            $relPath = $this->buildPdf($user, $ndaContent, $request, $signedAt, $ip, $cnicFrontPath, $cnicBackPath);

            if ($relPath) {
                DB::table('user')->where('id', $user->id)->update(['nda_document_path' => $relPath]);
                DB::table('hr_employees')->where('agent_id', $user->id)
                    ->update(['nda_document_url' => url($relPath)]);
            }
        } catch (\Throwable $e) {
            Log::warning('NDA PDF build failed; signature already saved and is regenerated on download', [
                'user_id' => $user->id, 'error' => $e->getMessage(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function download(int $userId)
    {
        $row      = DB::table('user')->where('id', $userId)->first();
        $filename = 'NDA_Signed_' . $userId . '.pdf';

        if (!$row) {
            abort(404, 'Signed NDA not found.');
        }

        // 1) File already on this account's disk (public/Uploads/… or storage/app/public/…).
        $path = $row->nda_document_path;
        if ($path) {
            foreach ([public_path($path), storage_path('app/public/' . $path)] as $full) {
                if ($full && is_file($full)) {
                    return response()->download($full, $filename, ['Content-Type' => 'application/pdf']);
                }
            }
        }

        // 2) Regenerate the PDF from the signed data held in the shared DB (works from either app,
        //    so a missing/relocated file can never 404 a signed NDA again).
        $hr = DB::table('hr_employees')->where('agent_id', $userId)->first();
        if ($hr && !empty($hr->nda_signature) && !empty($hr->nda_content)) {
            try {
                $user = \App\User::find($userId);
                $html = view('nda.pdf', [
                    'ndaContent'    => $hr->nda_content,
                    'brand'         => \App\Support\Brand::for($user),
                    'employeeName'  => $user->name ?? '',
                    'fatherName'    => $hr->nda_father_name ?? '',
                    'address'       => $hr->nda_address ?? '',
                    'cnic'          => $hr->cnic ?? '',
                    'signedDate'    => $hr->nda_signed_at ? date('d M Y H:i', strtotime($hr->nda_signed_at)) : '',
                    'signedIp'      => $hr->nda_signed_ip ?? '',
                    'signatureData' => $hr->nda_signature,
                    'cnicFrontPath' => $hr->nda_cnic_front ? public_path($hr->nda_cnic_front) : null,
                    'cnicBackPath'  => $hr->nda_cnic_back ? public_path($hr->nda_cnic_back) : null,
                ])->render();

                $dompdf = new Dompdf($this->pdfOptions());
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                return response($dompdf->output(), 200, [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]);
            } catch (\Throwable $e) {
                Log::error('NDA on-the-fly PDF failed', ['user_id' => $userId, 'error' => $e->getMessage()]);
            }
        }

        // 3) Last resort — the URL the signing app published (e.g. the HR portal serves it).
        if ($hr && !empty($hr->nda_document_url)) {
            return redirect()->away($hr->nda_document_url);
        }

        abort(404, 'Signed NDA file not found.');
    }

    /* ───────────────────────── helpers ───────────────────────── */

    private function pdfOptions(): Options
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        return $options;
    }

    private function storeUpload(Request $request, string $field, int $userId, $signedAt): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }
        $dir  = 'Uploads/nda_cnic';
        $full = public_path($dir);
        if (!file_exists($full)) {
            mkdir($full, 0755, true);
        }
        $file  = $request->file($field);
        $fname = $field . '_' . $userId . '_' . $signedAt->format('YmdHis') . '.' . $file->getClientOriginalExtension();
        $file->move($full, $fname);
        return $dir . '/' . $fname;
    }

    private function mirrorCnicDoc(int $hrEmployeeId, int $userId, int $settingId, ?string $relPath, $signedAt, bool $allowMultiple = false): void
    {
        if (!$relPath) {
            return;
        }
        try {
            // Multi-file settings (State ID front+back share setting 21) may hold several rows.
            if (!$allowMultiple) {
                $exists = DB::table('hr_employee_documents')
                    ->where('employee_id', $hrEmployeeId)
                    ->where('document_setting_id', $settingId)
                    ->exists();
                if ($exists) {
                    return;
                }
            }
            DB::table('hr_employee_documents')->insert([
                'employee_id'         => $hrEmployeeId,
                'document_setting_id' => $settingId,
                'file_path'           => $relPath,
                'file_name'           => basename($relPath),
                // hr_employee_documents.mime_type is NOT NULL — null made every CNIC mirror fail.
                'mime_type'           => $this->mimeFromExtension($relPath),
                'status'              => 0,
                'created_at'          => $signedAt,
                'updated_at'          => $signedAt,
            ]);
        } catch (\Throwable $e) {
            Log::warning('NDA mirror CNIC doc failed', ['user_id' => $userId, 'setting' => $settingId, 'error' => $e->getMessage()]);
        }
    }

    private function mimeFromExtension(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $map = [
            'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'jfif' => 'image/jpeg', 'png' => 'image/png',
            'gif' => 'image/gif', 'webp' => 'image/webp', 'pdf' => 'application/pdf',
        ];
        return $map[$ext] ?? 'application/octet-stream';
    }

    private function buildPdf($user, string $ndaContent, Request $request, $signedAt, string $ip, ?string $cnicFrontPath, ?string $cnicBackPath): ?string
    {
        try {
            $html = view('nda.pdf', [
                'ndaContent'    => $ndaContent,
                'brand'         => \App\Support\Brand::for($user),
                'employeeName'  => $request->employee_name,
                'fatherName'    => $request->father_name,
                'address'       => $request->address,
                'cnic'          => $request->cnic,
                'signedDate'    => $signedAt->format('d M Y H:i'),
                'signedIp'      => $ip,
                'signatureData' => $request->signature_data,
                'cnicFrontPath' => $cnicFrontPath ? public_path($cnicFrontPath) : null,
                'cnicBackPath'  => $cnicBackPath ? public_path($cnicBackPath) : null,
            ])->render();

            $dompdf = new Dompdf($this->pdfOptions());
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dir  = 'Uploads/nda_documents';
            $full = public_path($dir);
            if (!file_exists($full)) {
                mkdir($full, 0755, true);
            }
            $filename = 'nda_' . $user->id . '_' . $signedAt->format('YmdHis') . '.pdf';
            $relPath  = $dir . '/' . $filename;
            file_put_contents(public_path($relPath), $dompdf->output());

            return $relPath;
        } catch (\Throwable $e) {
            Log::error('NDA PDF generation failed (signature still recorded)', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return null;
        }
    }
}
