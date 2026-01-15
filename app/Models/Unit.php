<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'unit';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'short_name',
        'area',
        'type',
    ];   

}
