<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintMethod extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'complaint_method';
    public $timestamps  = true;

    protected $fillable = [

        'name',
        'num',
        'type',
    ];   

}
