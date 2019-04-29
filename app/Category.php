<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

  use Sluggable;

  protected $fillable = ['name'];

  /**
   * Return the sluggable configuration array for this model.
   *
   * @return array
   */
  public function sluggable(): array {
    return [
      'slug' => [
        'source' => 'name'
      ]
    ];
  }

  public function getRouteKeyName() {
    return 'slug';
  }

  public function posts(){
    return $this->hasMany(Post::class);
  }

}
