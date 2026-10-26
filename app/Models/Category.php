<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'marker_color',
        'icon',
        'description',
    ];

    /**
     * Relasi ke UMKM pada kategori ini
     */
    public function umkms(): HasMany
    {
        return $this->hasMany(TobaccoUmkm::class);
    }
}
