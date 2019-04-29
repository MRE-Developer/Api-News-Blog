<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  use Sluggable;

  protected $fillable = [
    'user_id',
    'category_id',
    'title',
    'body',
    'image',
    'resource',
    'tags',
    'slug',
    'viewCount',
    'commentCount',
  ];

  /**
   * Return the sluggable configuration array for this model.
   *
   * @return array
   */
  public function sluggable(): array {
    return [
      'slug' => [
        'source' => 'title'
      ]
    ];
  }

  public function getRouteKeyName() {
    return 'slug';
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function category() {
    return $this->belongsTo(Category::class);
  }

  /**
   * Get all of the post's comments.
   */
  public function comments()
  {
    return $this->morphMany(Comment::class, 'commentable');
  }

}