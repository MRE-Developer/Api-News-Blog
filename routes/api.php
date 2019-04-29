<?php

Route::group(['namespace' => 'Api\v1' , 'prefix' => 'v1'], function() {

    //Auth
  Route::group(['namespace' => 'Auth'] , function (){
    Route::post('/register' , 'RegisterController@store');
    Route::post('/register/verify' , 'RegisterController@verify');
    Route::post('/login' , 'LoginController@login');
    Route::post('/forgetPassword' , 'ForgetController@forgetPassword');
    Route::post('/forgetPassword/verify' , 'ForgetController@verify');
  });

    //Admin
  Route::group(['middleware' => ['auth:api' , 'checkAdmin'] , 'namespace' => 'Admin' , 'prefix' => 'admin'] , function () {

      //User
    Route::group(['middleware' => ['auth:api' , 'can:show-user'] , 'namespace' => 'User'] , function () {
      Route::get('/users', 'UserController@index');
      Route::post('/user', 'UserController@register');
      Route::post('/user/{user}', 'UserController@update');
      Route::get('/user/{user}', 'UserController@show');
      Route::post('/user/{user}/destroy', 'UserController@destroy');
      Route::get('/user/search', 'UserController@search'); // get name & mobile
    });

      //Category
    Route::group(['middleware' => ['auth:api', 'can:show-category'] , 'namespace' => 'category'] , function () {
      Route::get('/categories' , 'categoryController@index');
      Route::post('/category' , 'categoryController@store');
      Route::get('/category/{category}' , 'categoryController@show');
      Route::post('/category/{category}' , 'categoryController@update');
      Route::post('/category/{category}/destroy' , 'categoryController@destroy');
    });

      //Post
    Route::group(['middleware' => ['auth:api'] , 'namespace' => 'Post'] , function () {
      Route::get('/posts' , 'postController@index');
      Route::post('/post' , 'postController@store')->middleware('can:send-post');
      Route::get('/post/{post}' , 'postController@show');
      Route::post('/post/{post}' , 'postController@update')->middleware('can:edit-post');
      Route::post('/post/{post}/destroy' , 'postController@destroy')->middleware('can:edit-post');
    });

    //Comment
    Route::group(['middleware' => ['auth:api'] , 'namespace' => 'Comment'] , function () {
      Route::get('/comments' , 'CommentController@index');
      Route::post('/comment' , 'CommentController@store');
      Route::get('/comment/{comment}' , 'CommentController@show');
      Route::post('/comment/{comment}' , 'CommentController@update');
      Route::post('/comment/{comment}/destroy' , 'CommentController@destroy');
      Route::get('/comments/unsuccessful' , 'CommentController@unsuccessful');

    });

      //Role
    Route::group(['middleware' => ['auth:api' , 'can:show-role'] , 'namespace' => 'Auth'] , function () {
      Route::get('/roles' , 'RoleController@index');
      Route::post('/role' , 'RoleController@store');
      Route::get('/role/{role}' , 'RoleController@show');
      Route::post('/role/{role}' , 'RoleController@update');
      Route::post('/role/{role}/destroy' , 'RoleController@destroy');
    });

      //Permission
    Route::group(['middleware' => ['auth:api', 'can:show-permission'] , 'namespace' => 'Auth'] , function () {
      Route::get('/permissions' , 'PermissionController@index');
      Route::post('/permission' , 'PermissionController@store');
      Route::get('/permission/{permission}' , 'PermissionController@show');
      Route::post('/permission/{permission}' , 'PermissionController@update');
      Route::post('/permission/{permission}/destroy' , 'PermissionController@destroy');
    });

  });
  /*End Admin*/

    //Client
  Route::group(['middleware' => ['auth:api'] , 'namespace' => 'User'] , function (){
    Route::get('/profile' , 'UserController@show');
    Route::post('/profile' , 'UserController@update');
    Route::post('/profile/change_password' , 'UserController@changePassword');
    Route::post('/profile/destroy' , 'UserControzller@destroy');
  });

    //Public Route
  Route::get('/posts' , 'PostController@index');
  Route::get('/post/{post}' , 'PostController@show');
  Route::post('/comment' , 'HomeController@comment');
  Route::get('/categories' , 'CategoryController@index');
  Route::get('/category/{category}' , 'CategoryController@show');

});