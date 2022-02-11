<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLanguage extends Model
{
    protected $table = 'users_languages';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'language',
        'lang_level',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
