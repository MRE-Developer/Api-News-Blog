<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

  use Sluggable;

  protected $fillable = [
    'name' , 'slug' , 'label'
  ];

  protected $hidden = ['pivot'];

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

  public function roles() {
    return $this->belongsToMany(Role::class);
  }
}
