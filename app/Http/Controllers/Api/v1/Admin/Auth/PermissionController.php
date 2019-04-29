<?php

namespace App\Http\Controllers\Api\v1\Admin\Auth;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return response([
        'data' => Permission::latest()->get(),
        'status' => 'success'
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validator = \Validator::make($request->all(), [
        'name' => 'required|min:3',
        'label' => 'min:5',
      ]);

      if ($validator->fails()) {
        return response([
          'data' => $validator->errors(),
          'status' => 'error'
        ], 422);
      }

      return response([
        'data' =>  Permission::create($request->all()),
        'status' => 'success'
      ]);

    }

  /**
   * Display the specified resource.
   *
   * @param Permission $permission
   * @return \Illuminate\Http\Response
   */
    public function show(Permission $permission)
    {
      return response([
        'data' =>  $permission,
        'status' => 'success'
      ]);
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param Permission $permission
   * @return \Illuminate\Http\Response
   */
    public function update(Request $request, Permission $permission)
    {
      $validator = \Validator::make($request->all(), [
        'name' => 'required|min:3',
        'label' => 'min:5',
      ]);

      if ($validator->fails()) {
        return response([
          'data' => $validator->errors(),
          'status' => 'error'
        ], 422);
      }

      $permission->update($request->all());

      return response([
        'data' =>  $permission,
        'status' => 'success'
      ]);
    }

  /**
   * Remove the specified resource from storage.
   *
   * @param Permission $permission
   * @return \Illuminate\Http\Response
   * @throws \Exception
   */
    public function destroy(Permission $permission)
    {

      if ($permission->delete()){
        return response([
          'data' =>  "Permission Was Deleted",
          'status' => 'success'
        ]);
      }else{
        return response([
          'data' =>  "There is a Problem",
          'status' => 'error'
        ],400);
      }
    }
}
