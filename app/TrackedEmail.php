<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackedEmail extends Model
{
    protected $table = 'tracked_emails';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'subject',
        'message'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
