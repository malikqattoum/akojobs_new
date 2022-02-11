<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSource extends Model
{
    protected $table = 'users_sources';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'added_by_user_id',
        'email',
        'source',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
