<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicDocument extends Model
{

    protected $connection = 'mysql';
    protected $table    = 'documents';
    public $timestamps  = true;
    protected $fillable = [
        'name',
        'description',
        'category',
        'file_name',
        'file_path',
        'file_extension',
        'file_size',
        'download_count',
        'sort_order',
        'is_new',
        'status',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'sort_order' => 'integer',
        'is_new' => 'boolean',
        'status' => 'boolean',
    ];
}