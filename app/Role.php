<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  use Sluggable;

  protected $fillable = [
    'name' , 'slug' , 'label' ,
  ];

  protected $hidden = [
    'pivot'
  ];

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

  public function users() {
    return $this->belongsToMany(User::class);
  }

  public function permissions() {
    return $this->belongsToMany(Permission::class);
  }

}
