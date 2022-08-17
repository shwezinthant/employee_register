<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    function getEmployees(Request $request)
    {
        // return $request->query('search');
        $data = Employee::where('status',$request->query('status'))->where('name', 'LIKE', '%' . $request->query('search') . '%')->with('department')->get();
        return response()->json([
            'data' => $data
        ],200)->send() && exit(1);
    }
    function registerEmployees(Request $request)
    {
   if($request->id){
    $validation = Validator::make($request->all(),[ 
        'name' => 'required',
        'email'=>'required',
        'employee_id' => 'required',
        'status' => 'required',
        'department_id' => 'required',
        'accommodation_requests' => 'required',
    ]);
   }else{
    $validation = Validator::make($request->all(),[ 
        'name' => 'required',
        'email'=>'unique:employees,email,',
        'employee_id' => 'required',
        'status' => 'required',
        'department_id' => 'required',
        'accommodation_requests' => 'required',
        'file'=> 'required'
    ]);
   }
    
        if($validation->fails()){
            return response()->json([
                'data' => $validation->messages()
            ],400)->send() && exit(1);
           } 
           if($request->file){
            $employee_file = time().'.'.$request->file->extension();
            $request->file->move(public_path('file'),$employee_file);
            $employee_file_path= 'file/'.$employee_file;
           }else{
            $employee_file_path =null;
           }
     
           if($request->id==null){
            $employee=Employee::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'employee_id'=> $request->employee_id,
            'department_id'=> $request->department_id,
            'status'=> $request->status,
            'accommodation_requests'=> $request->accommodation_requests,
            'file'=> $employee_file_path
        ]);
           }else{
            $employee=Employee::find($request->id);
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->employee_id = $request->employee_id;
            $employee->department_id = $request->department_id;
            $employee->status = $request->status;
            $employee->accommodation_requests = $request->accommodation_requests;
         if($employee_file_path){
            $employee->file = $request->file;
         }
         $employee->save();
           }
    
        return response()->json([
            'data' => $employee
        ],201)->send() && exit(1);
    }
    function getEmployeesDetail($id)
    {
        $data= Employee::with('department')->findOrFail($id);
   
        return response()->json([
            'data' => $data
        ],200)->send() && exit(1);
    }
    function getDepartments()
    {
        $data= Department::all();

        return response()->json([
            'data' => $data
        ],200)->send() && exit(1);
    }
}