<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintType extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'complaint_type';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'num',
        'time_span',
        'type',
        'com_group'
    ];   

}
