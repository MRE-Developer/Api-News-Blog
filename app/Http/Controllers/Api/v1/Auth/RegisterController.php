<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Mobile;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{

  public function store(Request $request) {

        //Validation Data
    $validator = \Validator::make($request->all() , [
      'mobile' => 'required|min:11|max:11|unique:users',
      'password' => 'required|min:6'
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
          'password' => bcrypt($request->password),
        ]);
    }else{
      $mobile = Mobile::create([
        'mobile' => $request->mobile,
        'password' => bcrypt($request->password),
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


  public function verify(Request $request) {

    //Validation Data
    $validator = \Validator::make($request->all() , [
      'mobile' => 'required|min:11|max:11|exists:mobiles|unique:users',
      'verifyCode' => 'required|min:6:max:6|exists:mobiles'
    ]);

    if ($validator->fails()){
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $mobile = Mobile::whereMobile($request->mobile)->first();

    if ($mobile->verifyCode == $request->verifyCode){
      $mobile->update(['accepted' => "1"]);

      $user = User::create([
        'mobile' => $mobile->mobile,
        'name' => substr_replace($request->mobile , '***' , 4 , 3),
        'password' => $mobile->password,
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