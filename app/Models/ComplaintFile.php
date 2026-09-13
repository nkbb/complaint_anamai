<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ComplaintFile extends Model
{
    protected $fillable = [
        'complaint_id',
        'file_type',
        'original_name',
        'file_name',
        'file_path',
        'mime_type',
        'extension',
        'file_size',
        'sort_order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'file_url',
        'file_size_text',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(
            Complaints::class,
            'complaint_id',
            'id'
        );
    }

    public function getFileUrlAttribute(): string
    {
        if (!$this->file_path) {
            return '';
        }

        return '/storage/'
            . ltrim($this->file_path, '/');
    }

    public function getFileSizeTextAttribute(): string
    {
        $size = $this->file_size;

        if ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        }

        return number_format($size / 1024, 2) . ' KB';
    }
}