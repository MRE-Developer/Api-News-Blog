<?php

namespace App\Http\Controllers\Api\v1\Admin\User;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function Sodium\add;

class UserController extends Controller {
  //Index
  public function index() {

    return response([
      'data' => User::latest()->get(),
      'status' => 'success'
    ]);
  }

  public function register(Request $request) {

    //Validation Data
    $validator = \Validator::make($request->all(), [
      'name' => 'required|min:6',
      'mobile' => 'required|min:11|max:11|unique:users',
      'password' => 'required|min:6',
      'level' => 'required|min:4|max:5',
    ]);

    if ($validator->fails()) {
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $user = User::create([
      'mobile' => $request->mobile,
      'name' => $request->name,
      'level' => $request->level,
      'password' => bcrypt($request->password),
      'api_token' => Str::random(60),
    ]);

    $user->roles()->sync($request->role);
    $roles = $user->roles()->get();

    return response([
      'data' => [
        'user' => $user,
        'roles' => $roles
      ],
      'status' => 'success'
    ]);
  }

  public function show(User $user) {

    $roles = $user->roles()->get();

    return response([
      'data' => [
        'user' => $user,
        'roles' => $roles
      ],
      'status' => 'success'
    ]);
  }

  public function update(Request $request, User $user) {

    //Check Admin Manager
    if ($user->roles()->get()->pluck('name')->contains('manager')){
      if (!auth()->user()->roles()->get()->pluck('name')->contains('manager')){
        return redirect("api/v1/admin/users?api_token=$request->api_token");
      }
    }

    $validator = \Validator::make($request->all(), [
      'name' => 'required|min:3',
      'level' => 'required|min:4|max:5',
    ]);

    if ($validator->fails()) {
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $user->update([
      'name' => $request->name,
      'level' => $request->level,
    ]);

    $user->roles()->sync($request->role);
    $roles = $user->roles()->get();

    return response([
      'data' => [
        'user' => $user,
        'roles' => $roles
      ],
      'status' => 'success'
    ]);
  }

  public function destroy(Request $request, User $user){

    //Check Admin Manager
    if ($user->roles()->get()->pluck('name')->contains('manager')){
      if (!auth()->user()->roles()->get()->pluck('name')->contains('manager')){
        return redirect("api/v1/admin/users?api_token=$request->api_token");
      }
    }

    $user->delete();

    return response([
      'data' => "User Was Deleted",
      'status' => 'success'
    ]);
  }

  public function search(Request $request) {

    $users = User::where('name', 'LIKE', "%$request->name%")
      ->where('mobile', 'LIKE', "%$request->mobile%")->get();

    return response([
      'data' => $users,
      'status' => 'success'
    ]);

  }
}