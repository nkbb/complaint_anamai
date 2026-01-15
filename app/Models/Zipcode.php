<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zipcode extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'th_zipcode';
    public $timestamps  = true;

    protected $fillable = [

        'id',
        'subdistrict',
        'province_id',
        'district_id',
        'subdistrict_id',
        'zipcode',
    ];   

}
