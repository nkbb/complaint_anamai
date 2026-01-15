<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'th_district';
    public $timestamps  = true;

    protected $fillable = [
        
        'id',
        'name',
        'province_id',
    ];   

}
