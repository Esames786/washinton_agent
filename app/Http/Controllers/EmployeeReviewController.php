<?php

namespace App\Http\Controllers;

use App\Mail\AgentActivatedEmail;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmployeeReviewController extends Controller
{
    public function data(int $userId): JsonResponse
    {
        $agentUser = User::find($userId);
        if (!$agentUser) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $hrEmp = DB::table('hr_employees as e')
            ->leftJoin('hr_employee_statuses as s',  'e.employee_status_id', '=', 's.id')
            ->leftJoin('hr_shift_types as sh',        'e.shift_id',           '=', 'sh.id')
            ->leftJoin('hr_departments as d',          'e.department_id',      '=', 'd.id')
            ->leftJoin('hr_designations as dg',        'e.designation_id',     '=', 'dg.id')
            ->leftJoin('hr_employment_types as et',    'e.employment_type_id', '=', 'et.id')
            ->leftJoin('hr_commission_settings as cs', 'e.commission_id',      '=', 'cs.id')
            ->leftJoin('hr_commission_types as ct',    'cs.commission_type_id','=', 'ct.id')
            ->select(
                'e.id as hr_id',
                'e.full_name', 'e.email', 'e.phone', 'e.cnic', 'e.address',
                'e.profile_path', 'e.joining_date', 'e.basic_salary',
                'e.employee_code', 'e.gender', 'e.dob', 'e.city', 'e.state', 'e.country',
                'e.employee_status_id', 'e.father_name', 'e.marital_status',
                'e.skills', 'e.mother_name', 'e.phone2', 'e.emergency_contact',
                's.name as hr_status_name',
                'sh.name as shift_name', 'sh.shift_start', 'sh.shift_end',
                'd.name as department_name',
                'dg.name as designation_name',
                'et.name as employment_type_name',
                'cs.title as commission_title', 'cs.value as commission_value', 'cs.description as commission_desc',
                'ct.name as commission_type_name',
                'e.contract', 'e.contract_updated_at', 'e.contract_accepted_at'
            )
            ->where('e.agent_id', $userId)
            ->first();

        $documents   = [];
        $leaveQuotas = [];
        $hrStatuses  = DB::table('hr_employee_statuses')->select('id', 'name')->get();

        if ($hrEmp) {
            $documents = DB::table('hr_employee_documents as doc')
                ->leftJoin('hr_document_settings as ds', 'doc.document_setting_id', '=', 'ds.id')
                ->select('doc.id', 'doc.file_path', 'doc.file_name', 'doc.mime_type', 'doc.status', 'ds.title as doc_type')
                ->where('doc.employee_id', $hrEmp->hr_id)
                ->get();

            $leaveQuotas = DB::table('hr_employee_assign_leaves as al')
                ->leftJoin('hr_leave_types as lt', 'al.leave_type_id', '=', 'lt.id')
                ->select(
                    'lt.name as leave_type',
                    'al.assigned_quota', 'al.used_quota',
                    'al.valid_from', 'al.valid_to', 'al.status'
                )
                ->where('al.employee_id', $hrEmp->hr_id)
                ->where('al.status', 1)
                ->orderBy('lt.name')
                ->get();
        }

        return response()->json([
            'agent'       => [
                'id'     => $agentUser->id,
                'name'   => $agentUser->name,
                'slug'   => $agentUser->slug,
                'email'  => $agentUser->email,
                'phone'  => $agentUser->phone,
                'status' => (int) $agentUser->status,
            ],
            'hr_employee'  => $hrEmp,
            'documents'    => $documents,
            'leave_quotas' => $leaveQuotas,
            'hr_statuses'  => $hrStatuses,
            'hr_base_url'  => rtrim((string) config('bridge.hrportal.base_url'), '/'),
        ]);
    }

    public function changeAgentStatus(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'status' => 'required|in:0,1']);

        $user = User::findOrFail($request->user_id);
        $user->status = $request->status;
        $user->save();

        $emailNote = null;
        if ((int) $request->status === 1) {
            try {
                Mail::to($user->email)->send(new AgentActivatedEmail($user->name, $user->email));
                $emailNote = 'Activation email sent to ' . $user->email;
            } catch (\Throwable $e) {
                Log::warning('changeAgentStatus: activation email failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                $emailNote = 'Email failed: ' . $e->getMessage();
            }
        }

        return response()->json(['success' => true, 'status' => (int) $user->status, 'email_note' => $emailNote]);
    }

    public function changeHrStatus(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'hr_status_id' => 'required|integer']);

        $hrEmployee = DB::table('hr_employees')->where('agent_id', $request->user_id)->first();
        if (!$hrEmployee) {
            return response()->json(['error' => 'HR employee not found.'], 404);
        }

        // Block activation if any uploaded documents are unverified
        if ((int) $request->hr_status_id === 1) {
            $totalDocs      = DB::table('hr_employee_documents')->where('employee_id', $hrEmployee->id)->count();
            $unverifiedDocs = DB::table('hr_employee_documents')->where('employee_id', $hrEmployee->id)->where('status', 0)->count();
            if ($totalDocs > 0 && $unverifiedDocs > 0) {
                return response()->json([
                    'error' => "Cannot activate: {$unverifiedDocs} document(s) are not verified. Please verify all documents before activating."
                ], 422);
            }
        }

        $updated = DB::table('hr_employees')
            ->where('agent_id', $request->user_id)
            ->update(['employee_status_id' => $request->hr_status_id]);

        if (!$updated) {
            return response()->json(['error' => 'HR employee not found.'], 404);
        }

        $statusName = DB::table('hr_employee_statuses')->where('id', $request->hr_status_id)->value('name');

        if ((int) $request->hr_status_id === 1) {
            try {
                $agentUser = User::find($request->user_id);
                $emailTo   = $hrEmployee->email ?: ($agentUser ? $agentUser->email : null);
                $nameTo    = $hrEmployee->full_name ?: ($agentUser ? $agentUser->name : 'Employee');
                if ($emailTo) {
                    // Use the HR portal's mail class via a direct view since we share the DB
                    Mail::send('emails.hr_activated_agent', ['employeeName' => $nameTo, 'employeeEmail' => $emailTo], function ($m) use ($emailTo, $nameTo) {
                        $m->to($emailTo)->subject('Your Hello Transport HR Account is Now Active!');
                    });
                }
            } catch (\Throwable $e) {
                Log::warning('changeHrStatus: HR activation email failed', ['user_id' => $request->user_id, 'error' => $e->getMessage()]);
            }
        }

        return response()->json(['success' => true, 'hr_status_name' => $statusName]);
    }

    public function verifyDocument(Request $request): JsonResponse
    {
        $request->validate(['doc_id' => 'required|integer', 'status' => 'required|in:0,1']);

        $updated = DB::table('hr_employee_documents')
            ->where('id', $request->doc_id)
            ->update(['status' => $request->status]);

        if (!$updated) {
            return response()->json(['error' => 'Document not found.'], 404);
        }

        return response()->json(['success' => true]);
    }

    public function bulkVerifyDocuments(Request $request): JsonResponse
    {
        $request->validate(['hr_employee_id' => 'required|integer']);

        DB::table('hr_employee_documents')
            ->where('employee_id', $request->hr_employee_id)
            ->update(['status' => 1]);

        return response()->json(['success' => true, 'message' => 'All documents verified']);
    }

    public function saveContract(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'contract' => 'required|string']);

        $hrEmployee = DB::table('hr_employees')->where('agent_id', $request->user_id)->first();
        if (!$hrEmployee) {
            return response()->json(['error' => 'HR employee not found.'], 404);
        }

        DB::table('hr_employees')->where('agent_id', $request->user_id)->update([
            'contract'             => $request->contract,
            'contract_updated_at'  => now(),
            'contract_accepted_at' => null,
        ]);

        // Notify employee
        DB::table('notifications')->insert([
            'issueId'    => $hrEmployee->id,
            'userId'     => $request->user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Contract saved successfully']);
    }

    public function acceptContract(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer']);

        $affected = DB::table('hr_employees')->where('agent_id', $request->user_id)
            ->update(['contract_accepted_at' => now()]);

        if (!$affected) {
            return response()->json(['error' => 'HR employee not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Contract accepted']);
    }
}
