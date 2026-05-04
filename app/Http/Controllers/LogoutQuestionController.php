<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogoutQuestion;
use App\role;

class LogoutQuestionController extends Controller
{
    public function index()
    {
        $questions = LogoutQuestion::with('user_role')->get();
        return view('logout_questions.index', compact('questions'));
    }

    public function create()
    {
        $roles = role::all();
        return view('logout_questions.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'role' => 'required',
            'status' => 'required|integer',
        ]);

        LogoutQuestion::create($request->all());

        return redirect()->route('logout_questions.index')
            ->with('success', 'Question created successfully.');
    }

    public function edit($id)
    {
        $roles = role::all();
        $question = LogoutQuestion::findOrFail($id);
        return view('logout_questions.edit', compact('question', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'role' => 'required',
            'status' => 'required|integer',
        ]);

        $question = LogoutQuestion::findOrFail($id);
        $question->update($request->all());

        return redirect()->route('logout_questions.index')
            ->with('success', 'Question updated successfully.');
    }

    public function destroy($id)
    {
        $question = LogoutQuestion::findOrFail($id);
        $question->delete();

        return redirect()->route('logout_questions.index')
            ->with('success', 'Question deleted successfully.');
    }
}
