<?php

namespace App\Http\Controllers\Api\v1;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    return response([
      'data' => Category::latest()->get(),
      'status' => 'success'
    ]);
  }

  public function show(Category $category) {

    return response([
      'data' => [
        'category' => $category->name,
        'data' => $category->posts()->latest()->get()
      ],
      'status' => 'success'
    ]);
  }
}
