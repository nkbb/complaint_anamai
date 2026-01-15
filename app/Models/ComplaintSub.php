<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintSub extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'complaint_sub';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'complaint_type_id',
        'num',
        'type',
        'time_span'
    ];   

}
