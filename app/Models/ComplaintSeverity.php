<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintSeverity extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'complaint_severity';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'level',
        'type',
        'time',
    ];   

}
