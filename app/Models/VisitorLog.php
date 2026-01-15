<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    //
    protected $connection = 'mysql';
    protected $table    = 'visitor_logs';
    public $timestamps  = true;


    protected $fillable = [
        'ip_address', 'url', 'method', 'user_agent'
    ];
}
