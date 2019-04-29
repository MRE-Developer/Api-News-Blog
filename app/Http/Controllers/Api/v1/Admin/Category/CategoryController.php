<?php

namespace App\Http\Controllers\Api\v1\Admin\Category;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
          'data' => Category::latest()->get(),
          'status' => 'success'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validator = \Validator::make($request->all() , [
        'name' => 'required|min:3|max:15',
      ]);

      if ($validator->fails()){
        return response([
          'data' => $validator->errors(),
          'status' => 'error'
        ], 422);
      }

      $category = Category::create([
        'name' => $request->name
      ]);

      return response([
        'data' => $category,
        'status' => 'success'
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
      return response([
        'data' => [
          'category' => $category->name,
          'data' => $category->posts()->latest()->get()
        ],
        'status' => 'success'
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {

      $category->update($request->all());

      return response([
        'data' => $category,
        'status' => 'success'
      ]);
    }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Category $category
   * @return \Illuminate\Http\Response
   * @throws \Exception
   */
    public function destroy(Category $category)
    {
      $category->delete();

      return response([
        'data' => 'Category was Deleted',
        'status' => 'success'
      ]);
    }
}
