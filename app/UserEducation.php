<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    protected $table = 'users_educations';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'edu_degree',
        'edu_institution',
        'edu_grad_date',
        'on_going',
    ];

    protected $dates = ['created_at', 'updated_at', 'edu_grad_date', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(Models\User::class, 'user_id');
    }
}
