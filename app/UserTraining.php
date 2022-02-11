<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTraining extends Model
{
    protected $table = 'users_trainings';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'training_name',
        'training_institution',
        'training_completion',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'training_completion'];
}
