<?php

namespace App\Http\Controllers;

use App\PanelType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * B6 — admin screen to create / rename panels (city names).
 * Admin-only (role == 1). System panels (Testing, Website) cannot be renamed,
 * disabled or deleted. Numeric ids are never changed (they equal `paneltype`).
 */
class PanelTypeController extends Controller
{
    private function guardAdmin(): void
    {
        abort_unless(Auth::check() && (int) Auth::user()->role === 1, 403);
    }

    public function index()
    {
        $this->guardAdmin();
        $panels = PanelType::orderBy('sort')->orderBy('id')->get();

        return view('main.panel_types.index', compact('panels'));
    }

    public function store(Request $request)
    {
        $this->guardAdmin();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:panel_types,name',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $maxSort = (int) PanelType::max('sort');

        PanelType::create([
            'name'       => trim($request->name),
            'is_system'  => false,
            'is_default' => false,
            'sort'       => $maxSort + 1,
            'status'     => 1,
        ]);

        return back()->with('success', 'Panel created.');
    }

    public function update(Request $request, $id)
    {
        $this->guardAdmin();

        $panel = PanelType::findOrFail($id);

        // System panels (Testing, Website) are locked.
        if ($panel->is_system) {
            return back()->with('error', 'System panels cannot be modified.');
        }

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:100|unique:panel_types,name,' . $panel->id,
            'status' => 'nullable|in:0,1',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $panel->name   = trim($request->name);
        $panel->status = $request->has('status') ? (int) $request->status : $panel->status;
        $panel->save();

        return back()->with('success', 'Panel updated.');
    }
}
