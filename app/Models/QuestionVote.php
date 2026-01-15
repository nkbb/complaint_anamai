<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionVote extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'question_vote';
    public $timestamps  = true;

    protected $fillable = [

        'gender',
        'age',
        'work',
        'qualification',
        'work_dis',
        'ip',
    ];   

}
