<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'company';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'key_title',
        'address',
        'email',
        'dead_date_send',
        'dead_date_receive',
        'dead_date_answer',
        'dead_date_finish',
        'conditions',
    ];   

}
