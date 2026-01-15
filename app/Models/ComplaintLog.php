<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintLog extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'complaint_log';
    public $timestamps  = true;

    protected $fillable = [

        'date_time',
        'complaint_id',
        'user_id',
        'comment_id',
        'type',
    ];   

}
