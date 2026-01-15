<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    protected $connection = 'mysql';
    protected $table    = 'complaints';
    public $timestamps  = true;

    protected $fillable = [
        'code',
        'concealed',
        'fname',
        'lname',
        'gender',
        'idcard',
        'idcard_sub',
        'work',
        'address',
        'province_id',
        'district_id',
        'subdistrict_id',
        'zipcode',
        'tel',
        'phone',
        'email',
        'unit_id',
        'type_id',
        'sub_id',
        'person_id',
        'name',
        'description',
        'type',
        'method_id',
        'user_id',
        'file',
        'status',
        'improvement',
        'ip',
        'del_user',
        'del_date',
        'del_comm',
        'send_user',
        'send_date',
        'send_unit',
        'send_comm',
        'send_files',
        'receive_user',
        'receive_date',
        'auth_fname',
        'auth_lname',
        'auth_phone',
        'auth_email',
        'complain_level',
        'severity_admin',
        'trace_approve',
        'trace_show',
        'auth_email',
        'answer_detail',
        'answer_file',
        'answer_name',
        'answer_date',
        'is_add',
        'report_detail',
        'report_file',
        'report_name',
        'report_date',

    ];   

    public function hasUnit(){
        return $this->belongsTo('App\Models\Unit','unit_id');
    }

    public function hasPerson(){
        return $this->belongsTo('App\Models\ComplaintPerson','person_id');
    }

    public function hasType(){
        return $this->belongsTo('App\Models\ComplaintType','type_id');
    }

    public function hasSub(){
        return $this->belongsTo('App\Models\ComplaintSub','sub_id');
    }

}

