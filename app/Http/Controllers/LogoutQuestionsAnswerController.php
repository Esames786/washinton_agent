<?php

namespace App\Http\Controllers;

use App\LogoutQuestionsAnswer;
use App\LogoutQuestion;
use App\LogoutQuestionComments;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\general_setting;
use App\user_setting;
use App\User;

class LogoutQuestionsAnswerController extends Controller
{
    // public function index()
    // {
    //     $answers = LogoutQuestionsAnswer::with(['user', 'question', 'comments'])->groupBy('created_at')->get();
    //     // dd($answers->toArray());
    //     return view('logout_question_answers.index', compact('answers'));
    // }
    public function index(Request $request)
    {
        $groupedAnswers = LogoutQuestionsAnswer::with(['user', 'question', 'comments', 'comments.user'])
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d H:i:s');
            });
        
        $groupedData = [];
        
        
        
        foreach ($groupedAnswers as $createdAt => $answers) {
            $comments = $answers->flatMap->comments;
            $hasNegative = $comments->contains('verified', 0);
            $hasPositive = $comments->contains('verified', 1);
    
            $groupedData[] = [
                'created_at' => $createdAt,
                'user' => $answers->first()->user,
                'status' => $hasNegative ? 'Negative' : ($hasPositive ? 'Positive' : null),
            ];
        }
        
        // dd($groupedData);
    
        return view('logout_question_answers.index', compact('groupedData'));
    }

    public function create()
    {
        $paneltype = $this->check_panel();
        if($paneltype == 1)
        {
            $phoneaccess = explode(',',Auth::user()->emp_access_phone);
        }
        else
        {
            $phoneaccess = explode(',',Auth::user()->emp_access_web);
        }
        if(in_array("116", $phoneaccess) && Auth::user()->role != 1)
        {
            $role = Auth()->user()->role;
            $questions = LogoutQuestion::where('role', $role)->get();
            return view('logout_question_answers.create', compact('questions', 'role'));
        }
        else{
            Auth::logout();
            return redirect()->route('login');
        }
    }
    
    public function check_panel()
    {
        $setting = general_setting::first();
        $ptype = 1;
        $query = user_setting::where('user_id', Auth::user()->id)->where('created_at', '>=', Carbon::today()->subDays($setting->no_days))->first();
        if (!empty($query)) {
            $ptype = $query['penal_type'];
        }
        return $ptype;
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|array',
            'question_id.*' => 'exists:logout_questions,id',
            'answer' => 'required|array',
            'answer.*' => 'required|string',
        ]);
        $user_id = auth()->user()->id;
    
        $questions = $request->input('question_id');
        $answers = $request->input('answer');
    
        if (count($questions) !== count($answers)) {
            return redirect()->back()->withErrors(['msg' => 'Each question must have an answer.'])->withInput();
        }
    
        $newAnswers = [];
    
        foreach ($questions as $key => $question_id) {
            $newAnswers[] = [
                'user_id' => $user_id,
                'logout_question_id' => $question_id,
                'answer' => $answers[$key],
                'status' => 1,
            ];
        }
    
        LogoutQuestionsAnswer::insert($newAnswers);
    
        Auth::logout();
    
        // return back()->with('success', 'Logout question answers created successfully.');
        return redirect()->route('login');
    }

    public function getUserAns(Request $request)
    {
        $answers = LogoutQuestionsAnswer::with(['user', 'question', 'comments'])
            ->where('user_id', $request->user_id)
            ->where('created_at', $request->created_at)
            ->get();
        // dd($answers->toArray());
        return $answers;
    }

    public function storeQComment(Request $request)
    {
        $request->validate([
            'qa_id.*' => 'exists:logout_questions_answers,id',
            'qaComment' => 'required',
            'qaVerify.*' => 'required',
        ]);
        // dd($request->toArray());
        
        // dd('ok');
        
        $comment = new LogoutQuestionComments;
        $comment->user_id = Auth::id();
        $comment->qa_id = $request->qa_id;
        $comment->comment = $request->qaComment;
        $comment->verified = $request->qaVerify;
        $comment->save();
        
        // $answers = LogoutQuestionsAnswer::with(['user', 'question', 'comments'])->where('user_id', $request->user_id)->get();
        $answers = LogoutQuestionsAnswer::with(['user', 'question', 'comments'])
            ->where('user_id', $request->user_id)
            ->where('created_at', $request->created_at)
            ->get();
        return $answers;
    }
    
    public function filter(Request $request)
    {
        // if ($request->has('getAllNull') && $request->getAllNull == 'getAllNull')
        // {
        //     $usersWithoutAnswers = User::whereDoesntHave('logoutQuestionsAnswers')
        //     ->with(['logoutQuestionsAnswers'])
        //     ->get();
        // }
        // dd($usersWithoutAnswers->toArray());
        $paneltype = $this->check_panel();
        if($paneltype == 1)
        {
            $phoneaccess = explode(',',Auth::user()->emp_access_phone);
        }
        else
        {
            $phoneaccess = explode(',',Auth::user()->emp_access_web);
        }
        $dateRange = $request->input('date_range');
        $commentType = $request->input('comment_type');
        $verified = $request->input('verified');
        $approved = $request->input('approved');
    
        $startDate = null;
        $endDate = null;
        
        if ($dateRange) {
            list($startDate, $endDate) = explode(' - ', $dateRange);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();
        }
        
        $groupedAnswers = LogoutQuestionsAnswer::with(['user', 'question', 'comments'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d H:i:s');
            });
    
        $groupedData = [];
    
        foreach ($groupedAnswers as $createdAt => $answers) {
            $comments = $answers->flatMap->comments;
            $hasNegative = $comments->contains('verified', 0);
            $hasPositive = $comments->contains('verified', 1);
    
            $groupedData[] = [
                'created_at' => $createdAt,
                'user' => $answers->first()->user,
                // 'status' => $hasNegative ? 'Negative' : 'Positive',
                'status' => $hasNegative ? 'Negative' : ($hasPositive ? 'Positive' : null),

            ];
        }
    
        if ($verified !== null) {
            $filteredGroupedData = array_filter($groupedData, function ($row) use ($verified) {
                if ($verified == 0) {
                    return $row['status'] === 'Negative';
                } else {
                    return $row['status'] === 'Positive';
                }
            });
    
            $groupedData = $filteredGroupedData;
        }
    
        if ($approved !== null) {
            $filteredGroupedData = array_filter($groupedData, function ($row) use ($approved) {
                if ($approved == 0) {
                    return $row['status'] === null;
                } else {
                    return $row['status'] !== null;
                }
            });
    
            $groupedData = $filteredGroupedData;
        }
        
        // dd($approved, $groupedData);
        
        return view('logout_question_answers.table', compact('groupedData', 'paneltype', 'phoneaccess'));
    }

    public function getUserName(Request $request)
    {
        dd('ok');
        $name = User::find($request->id);
        dd($name);
        return $name;
    }
}
