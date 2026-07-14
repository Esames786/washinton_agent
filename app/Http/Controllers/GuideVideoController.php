<?php

namespace App\Http\Controllers;

use App\GuideVideo;
use App\user_setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class GuideVideoController extends Controller
{
    private const PERMISSION_MANAGE = '167';
    private const PERMISSION_VIEW   = '168';

    public function __construct()
    {
        $this->middleware('auth')->except('publicApi');
    }

    private function hasAccess(string $code): bool
    {
        $user = Auth::user();
        if ((int) $user->role === 1) return true;

        $ptype = user_setting::where('user_id', $user->id)->value('penal_type') ?? 1;

        // B6: accessForPanel() = same column for panels 1-6, link table for new panels (7+).
        return in_array($code, explode(',', (string) $user->accessForPanel((int) $ptype)));
    }

    // ── Management screen (permission 167) ───────────────────────────────────
    public function index(Request $request)
    {
        abort_unless($this->hasAccess(self::PERMISSION_MANAGE), 403);

        if ($request->ajax()) {
            $data = GuideVideo::with('user')->latest()->get();
            return DataTables::of($data)
                ->addColumn('uploaded_by', fn($row) => $row->user ? $row->user->name . ' ' . $row->user->last_name : '—')
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-warning btn-edit"
                            data-id="'.$row->id.'"
                            data-title="'.e($row->title).'"
                            data-description="'.e($row->description).'">
                            <i class="fe fe-edit"></i> Edit
                        </button>
                        <form method="POST" action="'.route('guide-videos.destroy', $row->id).'" style="display:inline"
                            onsubmit="return confirm(\'Delete this video?\')">
                            '.csrf_field().'
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="btn btn-sm btn-danger" type="submit">
                                <i class="fe fe-trash-2"></i> Delete
                            </button>
                        </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('main.guide_videos.index');
    }

    public function store(Request $request)
    {
        abort_unless($this->hasAccess(self::PERMISSION_MANAGE), 403);

        $rawFiles = $request->allFiles();
        $rawFile  = $rawFiles['video'] ?? null;

        Log::info('GuideVideo store: request received', [
            'user_id'          => Auth::id(),
            'title'            => $request->input('title'),
            'has_file'         => $request->hasFile('video'),
            'all_files'        => array_keys($rawFiles),
            'content_type'     => $request->header('Content-Type'),
            'raw_file_class'   => $rawFile ? get_class($rawFile) : null,
            'raw_is_valid'     => $rawFile ? $rawFile->isValid() : null,
            'raw_error_code'   => $rawFile ? $rawFile->getError() : null,
            'raw_orig_name'    => $rawFile ? $rawFile->getClientOriginalName() : null,
            'raw_real_path'    => $rawFile ? (string) $rawFile->getRealPath() : null,
            'upload_tmp_dir'   => ini_get('upload_tmp_dir'),
            'sys_temp_dir'     => sys_get_temp_dir(),
            'tmp_dir_writable' => is_writable(sys_get_temp_dir()),
        ]);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'video'       => 'required|file',
        ]);

        $file = $request->file('video');

        Log::info('GuideVideo store: file info', [
            'file_present'    => (bool) $file,
            'is_valid'        => $file ? $file->isValid() : null,
            'error_code'      => $file ? $file->getError() : null,
            'original_name'   => $file ? $file->getClientOriginalName() : null,
            'mime_type'       => $file ? $file->getMimeType() : null,
            'size_bytes'      => $file ? $file->getSize() : null,
            'tmp_path'        => $file ? $file->getRealPath() : null,
        ]);

        if (!$file || !$file->isValid()) {
            Log::error('GuideVideo store: file invalid', [
                'error_code' => $file ? $file->getError() : 'no file',
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Upload failed. Error code: ' . ($file ? $file->getError() : 'no file received'),
            ], 422);
        }

        try {
            $path = $file->store('guide_videos', 'public');
            Log::info('GuideVideo store: file stored', ['path' => $path]);
        } catch (\Throwable $e) {
            Log::error('GuideVideo store: storage failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Storage error: ' . $e->getMessage()], 500);
        }

        try {
            GuideVideo::create([
                'title'       => $request->title,
                'description' => $request->description,
                'filename'    => $path,
                'user_id'     => Auth::id(),
            ]);
            Log::info('GuideVideo store: record created', ['path' => $path, 'user_id' => Auth::id()]);
        } catch (\Throwable $e) {
            Log::error('GuideVideo store: DB insert failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Database error: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'message' => 'Video uploaded successfully.']);
    }

    public function update(Request $request, $id)
    {
        abort_unless($this->hasAccess(self::PERMISSION_MANAGE), 403);

        $video = GuideVideo::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'video'       => 'nullable|file',
        ]);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload failed. The file may exceed the server upload limit.',
                ], 422);
            }
            Storage::disk('public')->delete($video->filename);
            $video->filename = $file->store('guide_videos', 'public');
        }

        $video->title       = $request->title;
        $video->description = $request->description;
        $video->save();

        return response()->json(['success' => true, 'message' => 'Video updated successfully.']);
    }

    public function destroy($id)
    {
        abort_unless($this->hasAccess(self::PERMISSION_MANAGE), 403);

        $video = GuideVideo::findOrFail($id);
        Storage::disk('public')->delete($video->filename);
        $video->delete();

        return redirect()->back()->with('success', 'Video deleted successfully.');
    }

    // ── Viewer screen (permission 168) ────────────────────────────────────────
    public function viewer()
    {
        abort_unless($this->hasAccess(self::PERMISSION_VIEW), 403);

        // #5 (2026-07-03): guide videos are now assigned to agents selectively, same
        // as guides. Admin (1) / manager (9) see all; agents see only assigned videos.
        $user  = \Auth::user();
        $query = GuideVideo::with('user')->latest();
        if (!in_array((int) $user->role, [1, 9], true)) {
            $assigned = array_values(array_filter(explode(',', (string) $user->emp_access_guide_video), function ($v) {
                return $v !== '' && $v !== null;
            }));
            $query->whereIn('id', empty($assigned) ? [0] : $assigned);
        }

        $videos = $query->get();
        return view('main.guide_videos.viewer', compact('videos'));
    }

    // ── Public API for CrazyRays (no auth) ───────────────────────────────────
    public function publicApi(Request $request)
    {
        $query = GuideVideo::with('user')->latest();

        // Optional ?ids=28,29,30 filter — CrazyRays passes specific IDs to show
        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->input('ids'))));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $videos = $query->get()->map(fn($v) => [
            'id'          => $v->id,
            'title'       => $v->title,
            'description' => $v->description,
            'video_url'   => url('storage/' . $v->filename),
            'uploaded_by' => $v->user ? $v->user->name . ' ' . $v->user->last_name : 'Admin',
            'date'        => $v->created_at ? $v->created_at->format('M d, Y') : '',
        ]);

        return response()->json(['data' => $videos]);
    }
}
