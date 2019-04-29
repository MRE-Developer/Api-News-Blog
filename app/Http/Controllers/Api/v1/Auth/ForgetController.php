<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Mobile;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

class ForgetController extends Controller
{

  public function forgetPassword(Request $request) {

    //Validation Data
    $validator = Validator::make($request->all() , [
      'mobile' => 'required|min:11|max:11|exists:users',
    ]);

    if ($validator->fails()){
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    if ($mobile = Mobile::whereMobile($request->mobile)->first()){
      $mobile->update([
        'verifyCode' => rand(111111 , 999999),
      ]);
    }else{
      $mobile = Mobile::create([
        'mobile' => $request->mobile,
        'password' => bcrypt(rand(111111 , 999999)),
        'verifyCode' => rand(111111 , 999999),
      ]);
    }

    return response([
      'data' => [
        'mobile' => $mobile->mobile,
        'verifyCode' => $mobile->verifyCode
      ],
      'status' => 'success'
    ]);
  }

  public function verify(Request $request){

    //Validation Data
     $validator = Validator::make($request->all() , [
      'mobile' => 'required|min:11|max:11|exists:users',
      'verifyCode' => 'required|min:6|max:6|exists:mobiles',
      'password' => 'required|min:6',
    ]);

    if ($validator->fails()){
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $mobile = Mobile::whereMobile($request->mobile)->first();

    if ($mobile->verifyCode == $request->verifyCode){

      $mobile->update([
        'accepted' => "1",
        'password' => bcrypt($request->password),
      ]);

      $user = User::whereMobile($request->mobile)->first();

      $user->update([
        'password' => bcrypt($request->password),
        'api_token' => Str::random(60),
      ]);

      auth()->login($user);

      return response([
        'data' => auth()->user(),
        'status' => 'success'
      ]);

    }else{
      return response([
        'data' => [
          'verifyCode' => 'کد تایید وارد شده صحیح نیست'
        ],
        'status' => 'error'
      ], 422);
    }
  }
}
