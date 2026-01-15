<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'question';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'num',
        'type',
    ];   

}
