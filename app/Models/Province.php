<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'th_province';
    public $timestamps  = true;

    protected $fillable = [

        'id',
        'code',
        'name',
    ];   

}
