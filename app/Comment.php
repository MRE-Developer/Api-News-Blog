<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

  protected $fillable = [
    'name',
    'email',
    'comment',
    'approved',
    'parent_id',
    'commentable_id',
    'commentable_type',
  ];

  /**
   * Get all of the owning commentable models.
   */
  public function commentable()
  {
    return $this->morphTo();
  }

    /* Return Children of Comment*/
  public function comments() {
    return $this->hasMany(Comment::class , 'parent_id' , 'id')
           ->where('approved' , 1)->latest();
  }

    /* Add Enter & set <br> to Comment*/
  public function setCommentAttribute($value) {

    $this->attributes['comment'] = str_replace(PHP_EOL , "<br>" , $value);

  }
}
