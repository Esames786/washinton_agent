<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NdaController extends Controller
{
    public function sign(Request $request): JsonResponse
    {
        $request->validate([
            'employee_name'  => 'required|string|max:255',
            'cnic'           => 'required|string|max:20',
            'signature_data' => 'required|string',
            'agreed'         => 'required|in:1',
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
        $relPath  = null;

        // Attempt PDF generation — non-blocking (sign succeeds even if PDF fails)
        try {
            $html = view('nda.pdf', [
                'employeeName'  => $request->employee_name,
                'cnic'          => $request->cnic,
                'signedDate'    => $signedAt->format('d M Y H:i'),
                'signatureData' => $request->signature_data,
            ])->render();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dir      = 'nda_documents';
            $filename = 'nda_' . $user->id . '_' . $signedAt->format('YmdHis') . '.pdf';
            $relPath  = $dir . '/' . $filename;

            Storage::disk('public')->makeDirectory($dir);
            Storage::disk('public')->put($relPath, $dompdf->output());

        } catch (\Throwable $e) {
            Log::error('NDA PDF generation failed (signature still recorded)', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            $relPath = null;
        }

        // Clear NDA flag regardless of PDF outcome
        try {
            DB::table('user')
                ->where('id', $user->id)
                ->update([
                    'nda_required'      => 0,
                    'nda_signed_at'     => $signedAt,
                    'nda_document_path' => $relPath,
                ]);

            // Mirror signed NDA onto hr_employees so HR admin can see/download it.
            // Store a public URL (storage symlink) on the agent domain since the
            // HR portal is a separate app/session and cannot use the auth-only
            // /nda/download route.
            DB::table('hr_employees')
                ->where('agent_id', $user->id)
                ->update([
                    'nda_required'     => 0,
                    'nda_signed_at'    => $signedAt,
                    'nda_document_url' => $relPath ? url('storage/' . $relPath) : null,
                ]);

        } catch (\Throwable $e) {
            Log::error('NDA flag clear failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Server error saving signature. Please try again.'], 500);
        }

        return response()->json(['success' => true]);
    }

    public function download(int $userId)
    {
        $row = DB::table('user')->where('id', $userId)->first();

        if (!$row) {
            abort(404, 'Signed NDA not found.');
        }

        $path     = $row->nda_document_path;
        $filename = 'NDA_Signed_' . $userId . '.pdf';

        // The NDA may have been signed by EITHER app (agent portal writes
        // storage/app/public/nda_documents/…, the HR portal writes public/Uploads/nda_documents/…
        // under a DIFFERENT cPanel account). Resolve every scheme this side can actually reach,
        // and if the file lives on the HR account, redirect to its public URL instead of 404ing.
        if ($path) {
            $candidates = [
                public_path($path),                          // public/Uploads/nda_documents/…
                storage_path('app/public/' . $path),         // storage/app/public/nda_documents/…
                Storage::disk('public')->path($path),        // same as above via the disk
            ];
            foreach ($candidates as $full) {
                if ($full && is_file($full)) {
                    return response()->download($full, $filename, ['Content-Type' => 'application/pdf']);
                }
            }
        }

        // File isn't on this account's filesystem — fall back to the URL the signing app published
        // (e.g. the HR portal serves it at https://hr.…/Uploads/nda_documents/…).
        $hr = DB::table('hr_employees')->where('agent_id', $userId)->first();
        if ($hr && !empty($hr->nda_document_url)) {
            return redirect()->away($hr->nda_document_url);
        }

        abort(404, 'Signed NDA file not found.');
    }
}
