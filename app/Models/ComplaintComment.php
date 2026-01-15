<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintComment extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'complaint_comment';
    public $timestamps  = true;

    protected $fillable = [

        'ask_unit',
        'comment_unit',
        'user_ask',
        'user_com',
        'date_ask',
        'date_com',
        'type',
    ];   

}
