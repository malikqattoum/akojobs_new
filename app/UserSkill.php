<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    protected $table = 'users_skills';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'skill_name',
        'skill_level',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
