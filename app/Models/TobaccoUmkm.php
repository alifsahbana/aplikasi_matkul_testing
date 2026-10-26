<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class TobaccoUmkm extends Model
{
    use HasFactory;

    protected $table = 'tobacco_umkms';

    protected $fillable = [
        'code',
        'name',
        'owner_name',
        'category_id',
        'district_id',
        'address',
        'village',
        'latitude',
        'longitude',
        'land_area_ha',
        'production_capacity',
        'production_unit',
        'actual_production',
        'production_period',
        'worker_count',
        'transaction_value',
        'business_status',
        'data_source',
        'contact_phone',
        'is_verified',
        'created_by',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'land_area_ha' => 'float',
        'production_capacity' => 'float',
        'actual_production' => 'float',
        'transaction_value' => 'float',
        'worker_count' => 'integer',
        'is_verified' => 'boolean',
    ];

    /**
     * Relasi ke Kecamatan
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Relasi ke Kategori Rantai Pasok
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Pembuat / Verifikator
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Validasi batas koordinat Kabupaten Pamekasan
     * Lintang: -7.35 s/d -6.95
     * Bujur: 103.35 s/d 103.65
     */
    public static function isValidCoordinate(?float $lat, ?float $lng): bool
    {
        if ($lat === null || $lng === null) {
            return false;
        }

        return ($lat >= -7.3500000 && $lat <= -6.9500000) &&
               ($lng >= 103.3500000 && $lng <= 103.6500000);
    }

    /**
     * Hitung Utilisasi Kapasitas (%)
     */
    public function getCapacityUtilizationAttribute(): ?float
    {
        if ($this->production_capacity <= 0) {
            return null;
        }

        return round(($this->actual_production / $this->production_capacity) * 100, 2);
    }

    /**
     * Konversi data UMKM ke Leaflet GeoJSON Point Feature
     */
    public function toGeoJsonFeature(bool $maskOwner = false): array
    {
        return [
            'type' => 'Feature',
            'id' => $this->id,
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [(float) $this->longitude, (float) $this->latitude],
            ],
            'properties' => [
                'id' => $this->id,
                'code' => $this->code,
                'name' => $this->name,
                'owner_name' => $maskOwner && $this->owner_name ? mb_substr($this->owner_name, 0, 1) . '***' : $this->owner_name,
                'category_id' => $this->category_id,
                'category_name' => $this->category?->name ?? 'Tidak Terkategori',
                'marker_color' => $this->category?->marker_color ?? '#15803d',
                'district_id' => $this->district_id,
                'district_name' => $this->district?->name ?? '-',
                'village' => $this->village,
                'address' => $this->address,
                'production_capacity' => (float) $this->production_capacity,
                'actual_production' => (float) $this->actual_production,
                'production_unit' => $this->production_unit,
                'production_period' => $this->production_period,
                'utilization_rate' => $this->capacity_utilization,
                'worker_count' => (int) $this->worker_count,
                'transaction_value' => (float) $this->transaction_value,
                'business_status' => $this->business_status,
                'data_source' => $this->data_source,
                'contact_phone' => $this->contact_phone,
            ],
        ];
    }

    /**
     * Scope filter per kecamatan
     */
    public function scopeFilterDistrict(Builder $query, $districtId): Builder
    {
        if (!empty($districtId)) {
            return $query->where('district_id', $districtId);
        }
        return $query;
    }

    /**
     * Scope filter per kategori
     */
    public function scopeFilterCategory(Builder $query, $categoryId): Builder
    {
        if (!empty($categoryId)) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }
}
