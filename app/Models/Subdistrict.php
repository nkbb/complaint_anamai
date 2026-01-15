<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'th_subdistrict';
    public $timestamps  = true;

    protected $fillable = [

        'id',
        'zip_code',
        'name',
        'district_id',
        'province_id',
    ];   

}
