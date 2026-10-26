<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'latitude',
        'longitude',
        'area_km2',
        'geojson_boundary',
        'description',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'area_km2' => 'float',
        'geojson_boundary' => 'array',
    ];

    /**
     * Relasi ke UMKM di kecamatan ini
     */
    public function umkms(): HasMany
    {
        return $this->hasMany(TobaccoUmkm::class);
    }

    /**
     * Konversi ke format GeoJSON Feature
     */
    public function toGeoJsonFeature(): array
    {
        $geometry = $this->geojson_boundary ?? [
            'type' => 'Point',
            'coordinates' => [$this->longitude, $this->latitude],
        ];

        return [
            'type' => 'Feature',
            'id' => $this->id,
            'properties' => [
                'id' => $this->id,
                'code' => $this->code,
                'name' => $this->name,
                'area_km2' => $this->area_km2,
                'umkm_count' => $this->umkms_count ?? $this->umkms()->count(),
                'total_production' => (float) ($this->umkms()->sum('actual_production') ?? 0),
                'total_workers' => (int) ($this->umkms()->sum('worker_count') ?? 0),
            ],
            'geometry' => $geometry,
        ];
    }
}
