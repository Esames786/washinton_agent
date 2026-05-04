<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\role;
use App\RoleAccess;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = role::orderBy('id','DESC')->paginate(10);
        if($request->ajax())
        {
            return view('main.role.search',compact('role'));
        }
        return view('main.role.index',compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('main.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'role_name'=>'required|max:50|unique:roles,name',
            'status'=>'required'
        ]);
        if($validator->passes())
        {
            $total_emp_access_phone = "";
            $total_emp_access_web = "";
            $total_emp_access_test = "";
            $total_emp_show_data = "";
            $total_emp_access_ship = "";
            $total_emp_access_profile = "";
            $total_emp_access_action = "";
            $total_emp_access_report = "";
            $emp_access_phone = $request->emp_access_phone;
            $emp_access_web = $request->emp_access_web;
            $emp_access_test = $request->emp_access_test;
            $emp_show_data = $request->emp_show_data;
            $emp_access_ship = $request->emp_access_ship;
            $emp_access_profile = $request->emp_access_profile;
            $emp_access_action = $request->emp_access_action;
            $emp_access_report = $request->emp_access_report;
            // dd($request->emp_access_phone);
            if ($request->emp_access_phone <> null) {
                $total_emp_access_phone = implode(",", $emp_access_phone);
            }
    
            if ($request->emp_access_web <> null) {
                $total_emp_access_web = implode(",", $emp_access_web);
            }
            if ($request->emp_access_test <> null) {
                $total_emp_access_test = implode(",", $emp_access_test);
            }
            if ($request->emp_show_data <> null) {
                $total_emp_show_data = implode(",", $emp_show_data);
            }
            if ($request->emp_access_ship <> null) {
                $total_emp_access_ship = implode(",", $emp_access_ship);
            }
            if ($request->emp_access_profile <> null) {
                $total_emp_access_profile = implode(",", $emp_access_profile);
            }
            if ($request->emp_access_action <> null) {
                $total_emp_access_action = implode(",", $emp_access_action);
            }
            if ($request->emp_access_report <> null) {
                $total_emp_access_report = implode(",", $emp_access_report);
            }
            $data = new role;
            $data->name = $request->role_name;
            $data->slug = $request->role_name;
            $data->description = $request->role_name;
            $data->level = $request->status;
            $data->save();
            
            $role = new RoleAccess;
            $role->role_id = $data->id;
            $role->phone_access = $total_emp_access_phone;
            $role->web_access = $total_emp_access_web;
            $role->test_access = $total_emp_access_test;
            $role->show_data_access = $total_emp_show_data;
            $role->shipment_status_access = $total_emp_access_ship;
            $role->profile_access = $total_emp_access_profile;
            $role->emp_access_action = $total_emp_access_action;
            $role->emp_access_report = $total_emp_access_report;
            $role->save();
            
            return redirect('role');
        }
        else
        {
            return back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = role::with('access')->where('id',$id)->first();
        return view('main.role.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'role_name'=>'required|max:50|unique:roles,name,'.$id,
            'status'=>'required'
        ]);
        if($validator->passes())
        {
            $total_emp_access_phone = "";
            $total_emp_access_web = "";
            $total_emp_access_test = "";
            $total_emp_show_data = "";
            $total_emp_access_ship = "";
            $total_emp_access_profile = "";
            $total_emp_access_action = "";
            $total_emp_access_report = "";
            $emp_access_phone = $request->emp_access_phone;
            $emp_access_web = $request->emp_access_web;
            $emp_access_test = $request->emp_access_test;
            $emp_show_data = $request->emp_show_data;
            $emp_access_ship = $request->emp_access_ship;
            $emp_access_profile = $request->emp_access_profile;
            $emp_access_action = $request->emp_access_action;
            $emp_access_report = $request->emp_access_report;
            // dd($request->emp_access_phone);
            if ($request->emp_access_phone <> null) {
                $total_emp_access_phone = implode(",", $emp_access_phone);
            }
    
            if ($request->emp_access_web <> null) {
                $total_emp_access_web = implode(",", $emp_access_web);
            }
            if ($request->emp_access_test <> null) {
                $total_emp_access_test = implode(",", $emp_access_test);
            }
            if ($request->emp_show_data <> null) {
                $total_emp_show_data = implode(",", $emp_show_data);
            }
            if ($request->emp_access_ship <> null) {
                $total_emp_access_ship = implode(",", $emp_access_ship);
            }
            if ($request->emp_access_profile <> null) {
                $total_emp_access_profile = implode(",", $emp_access_profile);
            }
            if ($request->emp_access_action <> null) {
                $total_emp_access_action = implode(",", $emp_access_action);
            }
            if ($request->emp_access_report <> null) {
                $total_emp_access_report = implode(",", $emp_access_report);
            }
            $data = role::find($id);
            $data->name = $request->role_name;
            $data->slug = $request->role_name;
            $data->description = $request->role_name;
            $data->level = $request->status;
            $data->save();
            
            $role = RoleAccess::where('role_id',$id)->first();
            if(empty($role))
            {
                $role = new RoleAccess;
                $role->role_id = $id;
            }
            $role->phone_access = $total_emp_access_phone;
            $role->web_access = $total_emp_access_web;
            $role->test_access = $total_emp_access_test;
            $role->show_data_access = $total_emp_show_data;
            $role->shipment_status_access = $total_emp_access_ship;
            $role->profile_access = $total_emp_access_profile;
            $role->emp_access_action = $total_emp_access_action;
            $role->emp_access_report = $total_emp_access_report;
            $role->save();
            
            return redirect('role');
        }
        else
        {
            return back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = role::find($id);
        $data->level = $data->level == 1 ? 0 : 1;
        $data->save();
            
        return redirect('role');
    }
}
