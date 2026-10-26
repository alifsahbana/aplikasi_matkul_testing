<?php

namespace App\Services;

use App\Models\TobaccoUmkm;
use App\Models\District;

class GeoJsonService
{
    /**
     * Hasilkan GeoJSON FeatureCollection untuk Marker UMKM
     */
    public function getUmkmGeoJson(?int $districtId = null, ?int $categoryId = null, ?string $search = null, bool $maskOwner = true): array
    {
        $query = TobaccoUmkm::with(['district', 'category'])
            ->whereBetween('latitude', [-7.3500000, -6.9500000])
            ->whereBetween('longitude', [103.3500000, 103.6500000]);

        if ($districtId) {
            $query->where('district_id', $districtId);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('village', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $umkms = $query->get();

        $features = $umkms->map(function (TobaccoUmkm $item) use ($maskOwner) {
            return $item->toGeoJsonFeature($maskOwner);
        })->toArray();

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
            'metadata' => [
                'total_features' => count($features),
                'generated_at' => now()->toIso8601String(),
            ],
        ];
    }

    /**
     * Hasilkan GeoJSON FeatureCollection untuk Batas Wilayah Kecamatan
     */
    public function getDistrictsGeoJson(): array
    {
        $districts = District::withCount('umkms')->get();

        $features = $districts->map(function (District $district) {
            return $district->toGeoJsonFeature();
        })->toArray();

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
            'metadata' => [
                'region' => 'Kabupaten Pamekasan',
                'total_districts' => count($features),
                'crs' => 'EPSG:4326',
            ],
        ];
    }
}
