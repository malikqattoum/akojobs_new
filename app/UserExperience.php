<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    protected $table = 'users_experiences';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'exp_title',
        'exp_country',
        'from_date',
        'to_date',
        'exp_tasks',
        'company_name',
        'present'
    ];

    protected $dates = ['created_at', 'updated_at', 'from_date', 'to_date', 'deleted_at'];
    
}
