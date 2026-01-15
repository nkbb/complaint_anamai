<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telegram extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'sys_telegram';
    public $timestamps  = true;

    protected $fillable = [

        'token',
        'chat_id',
        'type',
        'unit_id',
    ];   

}
