<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CpanelEmail;
use App\User;

class CpanelEmailController extends Controller
{
    public function index()
    {
        $cpanelEmails = CpanelEmail::all();
        $users = User::with('userRole')->where('deleted', 0)->get();
        return view('cpanelemails.index', compact('cpanelEmails', 'users'));
    }

    public function create()
    {
        return view('cpanelemails.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'url'      => 'required',
            'email'    => 'required',
            'password' => 'required|min:6',
            'users'    => 'required|array',
            'users.*'  => 'exists:user,id',
            'status'   => 'nullable|boolean',
        ]);

        CpanelEmail::create([
            'name'     => $request->name,
            'url'      => $request->url,
            'email'    => $request->email,
            'password' => $request->password,
            'status'   => $request->status ?? 1,
            'users'    => json_encode($request->users),
        ]);

        return redirect()->route('cpanelemails.index')->with('success', 'Email created successfully.');
    }

    public function edit($id)
    {
        $cpanelEmail = CpanelEmail::findOrFail($id);
        return view('cpanelemails.edit', compact('cpanelEmail'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required',
            'url'      => 'required',
            'email'    => 'required',
            'password' => 'nullable|min:6',
            'users'    => 'required|array',
            'users.*'  => 'exists:user,id',
            'status'   => 'nullable|boolean',
        ]);

        $cpanelEmail = CpanelEmail::findOrFail($id);

        $data = [
            'name'   => $request->name,
            'url'    => $request->url,
            'email'  => $request->email,
            'status' => $request->status ?? $cpanelEmail->status,
            'users'  => json_encode($request->users),
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $cpanelEmail->update($data);

        return redirect()->route('cpanelemails.index')->with('success', 'Email updated successfully.');
    }

    public function destroy($id)
    {
        $cpanelEmail = CpanelEmail::findOrFail($id);
        $cpanelEmail->delete();

        return redirect()->route('cpanelemails.index')->with('success', 'Email deleted successfully.');
    }
}
