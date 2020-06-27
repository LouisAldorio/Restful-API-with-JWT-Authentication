<?php

namespace App\Http\Controllers\API\v1;

use App\Employee;
use JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\employeeResources;
use App\Http\Requests\employeeStoreRequest as StoreRequest;
use App\Http\Requests\employeeUpdateRequest as UpdateRequest;

class EmployeeController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = Employee::all();
        return response()->json([
            'status' => true,
            'message' => 'All Data fetched Correctly!',
            'employee' => employeeResources::collection($employee)
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $employee = Employee::create([
            'name' => $request->name,
            'email' => $request->email
        ]);
        if(!$employee){
          return response()->json(['message' => 'failed to store data!','status' => '404']);
        }
        return response()->json([
            'status' => true,
            'message' => 'Data has been stored Correctly',
            'employee' => new employeeResources($employee)
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::where('id',$id)->first();
        if(!$employee){
            return response()->json(['message' => 'ID not found!','status' => '404']);
        }
        return response()->json([
            'status' => true,
            'message' => 'Data Found!',
            'employee' => new employeeResources($employee)
        ],200);
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
        $employee = Employee::where('id',$id)->first();
        if(!$employee){
            return response()->json(['message' => 'ID not found!','status' => '404']);
        }
        $employee->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Data has been updated correctly!',
            'employee' => new employeeResources($employee)
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::where('id',$id)->first();
        if(!$employee){
            return response()->json(['message' => 'ID not found!','status' => '404']);
        }
        $employee->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data has been Deleted.'
        ],200);
    }
}
