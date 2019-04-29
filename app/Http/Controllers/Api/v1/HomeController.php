<?php

namespace App\Http\Controllers\Api\v1;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller {

  public function comment(Request $request) {

    $validator = \Validator::make($request->all(), [
      'name' => 'required|min:3',
      'email' => 'required|min:5|email',
      'comment' => 'required|min:3',
      'parent_id' => 'required',
      'commentable_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $comment = Comment::create(array_merge([
      'commentable_type' => 'App\Post'
    ], $request->all()));

    $post = Post::whereId($request->commentable_id)->first();
    $post->update([
      'commentCount' => $post->commentCount + 1
    ]);

    return response([
      'data' => $comment,
      'status' => 'success'
    ]);
  }
}
