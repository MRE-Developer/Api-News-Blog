<?php

namespace App\Http\Controllers\Api\v1;
use App\Post;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class PostController extends Controller {

  public function index() {

    //Delete Manual cache
    //cache()->flush();

    if (cache()->has('posts')) {
      $posts = cache('posts');
    } else {
      $posts = Post::with('category')->latest()->get();
      cache(['posts' => $posts], Carbon::now()->addMinutes(10));
    }

    return response([
      'data' => $posts,
      'status' => 'success'
    ]);
  }


  public function show(Post $post) {

    $post->update([
      'viewCount' => $post->viewCount + 1
    ]);

    $post['comments'] = $post->comments()->where('approved', 1)
      ->where('parent_id', 0)->latest()
      ->with('comments')->get();

    return response([
      'data' => $post,
      'status' => 'success'
    ]);

  }
}
