<?php

namespace App\Http\Controllers;

use App\EmailInlineUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MailInlineUploadController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        abort_if(!$user, 403, 'Unauthorized');

        $request->validate([
            'upload' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $file     = $request->file('upload');
        $disk     = 'public';
        $dir      = 'mail-inline-temp/' . $user->id;
        $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs($dir, $filename, $disk);
        $token    = Str::random(60);
        $url      = Storage::disk($disk)->url($path);

        $upload = EmailInlineUpload::create([
            'user_id'       => $user->id,
            'disk'          => $disk,
            'path'          => $path,
            'url'           => $url,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'token'         => $token,
        ]);

        return response()->json([
            'url' => $url . '?mail_inline_token=' . $upload->token,
        ]);
    }
}
