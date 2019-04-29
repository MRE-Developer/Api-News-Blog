<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'mobile', 'password', 'level', 'accepted', 'api_token'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'verifyCode'
  ];

  public function posts() {
    return $this->hasMany(Post::class);
  }

  public function isAdmin() {
    return auth()->user()->level === 'admin';
  }

  public function roles() {
    return $this->belongsToMany(Role::class);
  }

  public function hasRole($role){

    if(is_string($role)){

      return $this->roles->contains('name', $role );
    }

    return !! $role->intersect($this->roles)->count();
  }
}
