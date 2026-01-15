<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintPerson extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'complaint_person';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'num',
        'type',
    ];   

}
