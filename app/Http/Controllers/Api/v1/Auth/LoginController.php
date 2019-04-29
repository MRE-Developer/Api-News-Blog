<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{

  public function login(Request $request) {

    //Validation Data
    $validator = \Validator::make($request->all() , [
      'mobile' => 'required|min:11|max:11|exists:users',
      'password' => 'required|min:6'
    ]);

    if ($validator->fails()){
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $user = User::whereMobile($request->mobile)->first();

    if (Hash::check($request->password , $user->password)) {

      $user->update([
        'api_token' => Str::random(60)
      ]);

      auth()->login($user);

      return response([
        'data' => auth()->user(),
        'status' => 'success'
      ]);

    }else{
      return response([
        'data' => [
          'password' => 'رمز عبور صحیح نیست'
        ],
        'status' => 'error'
      ], 400);
    }
  }
}
