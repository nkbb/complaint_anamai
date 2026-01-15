<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'banner';
    public $timestamps  = true;

    protected $fillable = [

        'image',
        'uri',
        'type',
    ];   

}
