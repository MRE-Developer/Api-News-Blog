<?php

namespace App\Http\Controllers\Api\v1\Admin\Comment;

use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    return response([
      'data' => Comment::latest()->get(),
      'status' => 'success'
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $validator = \Validator::make($request->all(), [
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

    $comment = Comment::create([
      'name' => auth()->user()->name,
      'comment' => $request->comment,
      'parent_id' => $request->parent_id,
      'commentable_id' => $request->commentable_id,
      'commentable_type' => 'App\Post',
    ]);

    $post = Post::whereId($request->commentable_id)->first();
    $post->update([
      'commentCount' => $post->commentCount + 1
    ]);

    return response([
      'data' => $comment,
      'status' => 'success'
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param Comment $comment
   * @return \Illuminate\Http\Response
   */
  public function show(Comment $comment) {
    return response([
      'data' => $comment,
      'status' => 'success'
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param Comment $comment
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Comment $comment) {

    $validator = \Validator::make($request->all(), [
      'name' => 'required|min:3',
      'email' => 'min:10',
      'comment' => 'required|min:3',
      'parent_id' => 'required',
      'approved' => 'required|max:1',
      'commentable_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $comment->update(array_merge([
      'commentable_type' => 'App\Post'
    ], $request->all()));

    return response([
      'data' => $comment,
      'status' => 'success'
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Comment $comment
   * @return \Illuminate\Http\Response
   * @throws \Exception
   */
  public function destroy(Comment $comment) {
    $comment->delete();

    return response([
      'data' => "Comment Was Deleted",
      'status' => 'success'
    ]);
  }

  public function unsuccessful() {

    $comments = Comment::whereApproved(0)->get();
    return response([
      'data' => $comments,
      'status' => 'success'
    ]);
  }
}
