<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_filename',
        'total_rows',
        'success_rows',
        'failed_rows',
        'error_details',
        'status',
    ];

    protected $casts = [
        'total_rows' => 'integer',
        'success_rows' => 'integer',
        'failed_rows' => 'integer',
        'error_details' => 'array',
    ];

    /**
     * Relasi ke User pengunggah berkas
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
