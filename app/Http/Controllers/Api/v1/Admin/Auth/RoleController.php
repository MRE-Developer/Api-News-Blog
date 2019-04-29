<?php

namespace App\Http\Controllers\Api\v1\Admin\Auth;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return response([
        'data' => Role::latest()->get(),
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

      $role = Role::create($request->all());
      $role->permissions()->sync($request->permission);
      $permissions = $role->permissions()->get();

      return response([
        'data' => [
          'role' => $role,
          'permissions' => $permissions
        ],
        'status' => 'success'
      ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
      $permissions = $role->permissions()->get();
      return response([
        'data' => [
          'role' => $role,
          'permissions' => $permissions
        ],
        'status' => 'success'
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
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

      $role->update($request->all());
      $role->permissions()->sync($request->permission);
      $permissions = $role->permissions()->get();

      return response([
        'data' => [
          'role' => $role,
          'permissions' => $permissions
        ],
        'status' => 'success'
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {

      if ($role->delete()){
        return response([
          'data' =>  "Role Was Deleted",
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
