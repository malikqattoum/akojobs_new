<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVideo extends Model
{
    protected $table = 'users_videos';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'video',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
