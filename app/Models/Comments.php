<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'comments';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'comment',
        'type_id',
        'sub_id',
        'ip',
    ];   

}
