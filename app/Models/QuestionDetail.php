<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionDetail extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'question_detail';
    public $timestamps  = true;

    protected $fillable = [

        'question_id',
        'vote_id',
        'score',
    ];   

}
