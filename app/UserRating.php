<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    protected $table = 'users_ratings';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'rated_by_user_id',
        'user_id',
        'rating',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
