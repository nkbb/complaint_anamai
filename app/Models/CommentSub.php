<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentSub extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'comment_sub';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'num',
        'type',
        'comment_type_id',
    ];   

}
