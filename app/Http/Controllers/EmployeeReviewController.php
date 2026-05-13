<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                'ct.name as commission_type_name'
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
            'hr_employee' => $hrEmp,
            'documents'   => $documents,
            'leave_quotas'=> $leaveQuotas,
            'hr_statuses' => $hrStatuses,
        ]);
    }

    public function changeAgentStatus(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'status' => 'required|in:0,1']);

        $user = User::findOrFail($request->user_id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true, 'status' => (int) $user->status]);
    }

    public function changeHrStatus(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'hr_status_id' => 'required|integer']);

        $updated = DB::table('hr_employees')
            ->where('agent_id', $request->user_id)
            ->update(['employee_status_id' => $request->hr_status_id]);

        if (!$updated) {
            return response()->json(['error' => 'HR employee not found.'], 404);
        }

        $statusName = DB::table('hr_employee_statuses')->where('id', $request->hr_status_id)->value('name');

        return response()->json(['success' => true, 'hr_status_name' => $statusName]);
    }
}
