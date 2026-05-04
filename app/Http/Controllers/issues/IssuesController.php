<?php

namespace App\Http\Controllers\issues;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\chat;
use App\role;
use App\issues;
use App\issue_chats;
use App\notifications;
use App\zipcodes;
use Session;
use Redirect;
use Hash;
use Mail;
use Auth;
use DB;
use Vinkla\Hashids\Facades\Hashids;

class IssuesController extends Controller
{

    public function issues_add()
    {

        $data = User::all();

        if (Auth::check()) {
            return view('main.issues.index', compact('data'));
        } else {
            return redirect('/loginn/');
        }
    }

    public function save_issue(Request $request)
    {
        //dd($request);
        $issue = new issues();
        $issue->createduserId = Auth::user()->id;
        $issue->issuewithuserId = implode(',', $request->issues_with_users);
        $issue->subject = $request->subject;
        $issue->detail = $request->issuedetail;
        if (Auth::user()->role == 1) {
            $issue->status = 'Approved';
        } else {
            $issue->status = 'Pending';
        }
        $issue->save();
        if (Auth::user()->role == 1) {
            if(isset($request->other_emp) && !empty($request->other_emp) ) {
                $users = User::all();
                foreach ($users as $user) {
                    if ($user->id != Auth::user()->id) {
                        $notifications = new notifications();
                        $notifications->issueId = $issue->id;
                        $notifications->userId = $user->id;
                        $notifications->save();
                    }
                }
            }
        }

        Session::flash('flash_message', 'Employee Data Successfully Saved');
        return "SUCCESS";
    }

    public function my_issues()
    {

        $data['issues'] = issues::where('createduserId', Auth::user()->id)
            ->orderBy('created_at')->get();
        $data['users'] = User::all();

        $data['penaltyusers'] = issues::where([
            ['createduserId', '=', Auth::user()->id],
            ['penalty', '<>', null]
        ])->get();


        if (Auth::check()) {
            return view('main.issues.my_issues', $data);
        } else {
            return redirect('/loginn/');
        }

    }

    public function admin_issues()
    {
        $data['issues'] = issues::orderBy('created_at', 'desc')->get();
        $data['users'] = User::all();

        if (Auth::check()) {
            return view('main.issues.admin_issues_list', $data);
        } else {
            return redirect('/loginn/');
        }

    }

    public function issue_approve($id)
    {
        $emp = issues::find($id);
        $emp->status = 'Approved';
        $emp->approve_date = date('Y-m-d');
        $emp->save();

        $users = User::all();
        foreach ($users as $user) {
            if($user->id != Auth::user()->id) {
                $notifications = new notifications();
                $notifications->issueId = $id;
                $notifications->userId = $user->id;
                $notifications->save();
            }
        }

        return redirect('/admin_issues');
    }

    public function issue_reject($id)
    {
        $emp = issues::find($id);
        $emp->status = 'Rejected';
        $emp->approve_date = date('Y-m-d');
        $emp->save();
        return redirect('/admin_issues');

    }

    public function get_notification(Request $request)
    {
        $data = issues::where([
            ['nf.status', '=', '0'],
            ['issues.status', '=', 'Approved'],
            ['nf.userId', '=', Auth::user()->id]
        ])
            ->join('notifications as nf', 'issues.id', '=', 'nf.issueId')
            ->select('issues.id as issueId', 'issues.subject as subject', 'issues.detail as detail', 'nf.id as notificationId')
            ->get();

        return response()->json($data, 200);
    }

    public function issue_comments_list()
    {

        $data['issues'] = issues::where('status', 'Approved')
            ->orderBy('created_at', 'desc')->get();
        $data['users'] = User::all();
        $data['issuescomments'] = issue_chats::where('userId', Auth::user()->id)->get();
        $data['notifications'] = notifications::where('userId', Auth::user()->id)->get();

        if (Auth::check()) {
            return view('main.issues.issues_comments_list', $data);
        } else {
            return redirect('/loginn/');
        }

    }

    public function issue_comments_add($id)
    {
        $issueid = $id;

        $users = User::all();
        $issues = issues::where('status', 'Approved')->where('id',$issueid)
            ->orderBy('created_at', 'desc')->first();

        $chat = issue_chats::where([
            ['issueId', '=', $issueid],
            ['userId', '=', Auth::user()->id]
        ])
            ->select('comments')
            ->first();
        //dd($chat);
        if (Auth::check()) {
            return view('main.issues.issues_comments_add', compact('issueid', 'chat','issues','users'));
        } else {
            return redirect('/loginn/');
        }

    }

    public function issue_comments_store(Request $request)
    {
        //dd($request);

        $issuechat = issue_chats::where('userId', Auth::user()->id)->where('issueId', $request->issueid)->first();

        if (empty($issuechat)) {
            $issuechat = new issue_chats();
            $issuechat->issueId = $request->issueid;
            $issuechat->userId = Auth::user()->id;
            $issuechat->comments = $request->comments;
            $issuechat->save();


            $update = notifications::where([
                ['issueId', '=', $request->issueid],
                ['userId', '=', Auth::user()->id]
            ])->first();
            $update->status = '1';
            $update->save();
            Session::flash('flash_message', 'Comments Successfully Saved');


        } else {
            Session::flash('flash_message', 'ALREADY COMMENTED');
        }
        return "SUCCESS";

    }

    public function issue_comments_done($id)
    {
        $notificationdone = notifications::where('issueId',$id)->where('userId',Auth::user()->id)->first();
        $notificationdone->status = '1';
        $notificationdone->doneby = Auth::user()->id;
        $notificationdone->save();
        return redirect('/issue_comments_list');

    }

    public function issue_view_admin($id)
    {

        $idd = $id;
        $issues = issues::where('id', $id)->first();
        $users = User::all();
        $issuescomments = issue_chats::where('issueId', $id)->get();


//        foreach ($data['issues'] as $issue) {
//            $data['issuesId'] = explode(',', $issue->issuewithuserId);
//
//        }
//        foreach ($data['issues'] as $issue) {
//            if ($issue->penalty <> null) {
//                $data['penaltyusers'] = explode(',', $issue->penaltyusers);
//            }
//        }

        if (Auth::check()) {
            return view('main.issues.issues_view_admin', compact('users', 'issuescomments', 'issues', 'idd'));
        } else {
            return redirect('/loginn/');
        }

    }

    public function issue_penalty_store(Request $request)
    {

        $issuepenalty = issues::find($request->issueId);
        $issuepenalty->status = 'Closed';
        $issuepenalty->penaltyusers = implode(',', $request->penaltyusers);
        $issuepenalty->penalty = $request->penalty;
        $issuepenalty->save();

        Session::flash('flash_message', 'Successfully Saved');
        return "SUCCESS";

    }

}
