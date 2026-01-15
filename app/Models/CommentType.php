<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentType extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'comment_type';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'num',
        'type',
    ];   

}
