<?php

namespace App\Http\Controllers\Api\v1\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use function Sodium\add;

class UserController extends Controller
{
    //Index
  public function index() {

    return response([
      'data' => User::latest()->paginate(15),
      'status' => 'success'
    ]);
  }

    //Show
  public function show() {

    return response([
      'data' => \Auth::user(),
      'status' => 'success'
    ]);
  }

    //Update
  public function update(Request $request){

    $validator = \Validator::make($request->all() , [
      'name' => 'required|min:3|max:15',
    ]);

    if ($validator->fails()){
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $user = Auth::user();
    $user->update([
      'name' => $request->name
    ]);

    return response([
      'data' => $user,
      'status' => 'success'
    ]);
  }

  public function changePassword(Request $request){

    $validator = \Validator::make($request->all() , [
      'password' => 'required|min:6'
    ]);

    if ($validator->fails()){
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $user = Auth::user();
      $user->update([
        'password' => bcrypt($request->password),
      ]);

    return response([
      'data' => $user,
      'status' => 'success'
    ]);

  }

  public function destroy() {

    Auth::user()->delete();

    return response([
      'data' => "User Deleted ",
      'status' => 'success'
    ]);
  }
}