<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReference extends Model
{
    protected $table = 'users_references';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'ref_name',
        'ref_position',
        'ref_company',
        'ref_email',
        'ref_phone',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
