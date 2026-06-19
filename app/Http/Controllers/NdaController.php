<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
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

        try {
            // Strip the data URI prefix: data:image/png;base64,...
            $sigBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $request->signature_data);
            $sigBinary = base64_decode($sigBase64);
            if (!$sigBinary || strlen($sigBinary) < 100) {
                return response()->json(['success' => false, 'message' => 'Invalid signature. Please draw again.']);
            }

            // Generate PDF
            $pdf = Pdf::loadView('nda.pdf', [
                'employeeName'  => $request->employee_name,
                'cnic'          => $request->cnic,
                'signedDate'    => now()->format('d M Y H:i'),
                'signatureData' => $request->signature_data,
            ])->setPaper('a4', 'portrait');

            // Save PDF
            $dir      = 'nda_documents';
            $filename = 'nda_' . $user->id . '_' . now()->format('YmdHis') . '.pdf';
            $relPath  = $dir . '/' . $filename;

            Storage::disk('public')->makeDirectory($dir);
            Storage::disk('public')->put($relPath, $pdf->output());

            // Clear NDA flag on user + mirrored hr_employees record (same DB)
            DB::table('user')
                ->where('id', $user->id)
                ->update([
                    'nda_required'      => 0,
                    'nda_signed_at'     => now(),
                    'nda_document_path' => $relPath,
                ]);

            DB::table('hr_employees')
                ->where('agent_id', $user->id)
                ->update(['nda_required' => 0]);

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            Log::error('NDA sign failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Server error. Please try again.'], 500);
        }
    }

    public function download(int $userId)
    {
        $row = DB::table('user')->where('id', $userId)->first();

        if (!$row || !$row->nda_document_path) {
            abort(404, 'Signed NDA not found.');
        }

        if (!Storage::disk('public')->exists($row->nda_document_path)) {
            abort(404, 'File not found on server.');
        }

        $fullPath = Storage::disk('public')->path($row->nda_document_path);
        $filename = 'NDA_Signed_' . $userId . '.pdf';

        return response()->download($fullPath, $filename, ['Content-Type' => 'application/pdf']);
    }
}
