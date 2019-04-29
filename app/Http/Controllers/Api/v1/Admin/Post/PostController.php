<?php

namespace App\Http\Controllers\Api\v1\Admin\Post;

use App\Http\Controllers;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends Controllers\Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    return response([
      'data' => Post::with('category')->latest()->get(),
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
      'title' => 'required|min:10',
      'body' => 'required|min:20',
      'category' => 'required',
      'image' => 'required|mimes:jpeg,png,jpg,bmp|max:300',
      'tags' => 'required',
      'resource' => 'required',
    ]);

    if ($validator->fails()) {
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $imageUrl = $this->uploadImage($request->file('image'));

    $post = auth()->user()->posts()->create([
      'title' => $request->title,
      'body' => $request->body,
      'category_id' => $request->category,
      'image' => $imageUrl,
      'tags' => $request->tags,
      'resource' => $request->resource,
    ]);

    return response([
      'data' => $post,
      'status' => 'success'
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function show(Post $post) {
    $post->update([
      'viewCount' => $post->viewCount + 1
    ]);

    return response([
      'data' => [
        'post' => $post,
        'category' => $post->category()->first()
      ],
      'status' => 'success'
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Post $post) {
    $validator = \Validator::make($request->all(), [
      'title' => 'required|min:10',
      'body' => 'required|min:20',
      'category' => 'required',
      'tags' => 'required',
      'resource' => 'required',
    ]);

    if ($validator->fails()) {
      return response([
        'data' => $validator->errors(),
        'status' => 'error'
      ], 422);
    }

    $file = $request->file('image');
    if ($file) {
      $imageUrl = $this->uploadImage($file);
    } else {
      $imageUrl = $post->image;
    }

    $post->update([
      'title' => $request->title,
      'body' => $request->body,
      'category_id' => $request->category,
      'image' => $imageUrl,
      'tags' => $request->tags,
      'resource' => $request->resource,
    ]);

    return response([
      'data' => $post,
      'status' => 'success'
    ]);

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   * @throws \Exception
   */
  public function destroy(Post $post) {
    $post->delete();

    return response([
      'data' => "Post Was Deleted",
      'status' => 'success'
    ]);
  }

  protected function uploadImage($file) {
    $year = Carbon::now()->year;
    $month = Carbon::now()->month;
    $imagePath = "/upload/images/{$year}/{$month}/";
    $filename = $file->getClientOriginalName();

    $file->move(public_path($imagePath), $filename);

    return $imagePath . $filename;
  }
}