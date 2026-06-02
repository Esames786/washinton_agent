<?php

namespace App\Http\Controllers;

use App\GuideVideo;
use App\user_setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class GuideVideoController extends Controller
{
    private const PERMISSION_MANAGE = '167';
    private const PERMISSION_VIEW   = '168';

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function hasAccess(string $code): bool
    {
        $user = Auth::user();
        if ((int) $user->role === 1) return true;

        $ptype = user_setting::where('user_id', $user->id)->value('penal_type') ?? 1;

        $col = match ((int) $ptype) {
            2 => 'emp_access_web',
            3 => 'emp_access_test',
            4 => 'panel_type_4',
            5 => 'panel_type_5',
            6 => 'panel_type_6',
            default => 'emp_access_phone',
        };

        return in_array($code, explode(',', $user->$col ?? ''));
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

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'video'       => 'required|file|mimetypes:video/mp4,video/avi,video/mov,video/wmv,video/webm|max:204800',
        ]);

        $path = $request->file('video')->store('guide_videos', 'public');

        GuideVideo::create([
            'title'       => $request->title,
            'description' => $request->description,
            'filename'    => $path,
            'user_id'     => Auth::id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Video uploaded successfully.']);
    }

    public function update(Request $request, $id)
    {
        abort_unless($this->hasAccess(self::PERMISSION_MANAGE), 403);

        $video = GuideVideo::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'video'       => 'nullable|file|mimetypes:video/mp4,video/avi,video/mov,video/wmv,video/webm|max:204800',
        ]);

        if ($request->hasFile('video')) {
            Storage::disk('public')->delete($video->filename);
            $video->filename = $request->file('video')->store('guide_videos', 'public');
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

        $videos = GuideVideo::with('user')->latest()->get();
        return view('main.guide_videos.viewer', compact('videos'));
    }
}
