<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeoJsonService;
use App\Models\District;
use App\Models\Category;
use App\Models\TobaccoUmkm;

class MapController extends Controller
{
    public function __construct(
        protected GeoJsonService $geoJsonService
    ) {}

    /**
     * Halaman Peta WebGIS Interaktif
     */
    public function index(Request $request)
    {
        $districts = District::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $selectedDistrict = $request->query('district');
        $selectedCategory = $request->query('category');
        $searchQuery = $request->query('q');

        return view('public.map', compact('districts', 'categories', 'selectedDistrict', 'selectedCategory', 'searchQuery'));
    }

    /**
     * Endpoint API Data GeoJSON Marker UMKM
     */
    public function geojsonUmkms(Request $request)
    {
        $districtId = $request->query('district_id') ? (int) $request->query('district_id') : null;
        $categoryId = $request->query('category_id') ? (int) $request->query('category_id') : null;
        $search = $request->query('q');

        $geojson = $this->geoJsonService->getUmkmGeoJson($districtId, $categoryId, $search, maskOwner: true);

        return response()->json($geojson);
    }

    /**
     * Endpoint API Data GeoJSON Batas Wilayah Kecamatan
     */
    public function geojsonDistricts()
    {
        $geojson = $this->geoJsonService->getDistrictsGeoJson();

        return response()->json($geojson);
    }

    /**
     * Detail Tunggal UMKM (untuk modal/drawer)
     */
    public function showUmkm(TobaccoUmkm $umkm)
    {
        $umkm->load(['district', 'category']);

        return response()->json([
            'id' => $umkm->id,
            'code' => $umkm->code,
            'name' => $umkm->name,
            'category' => $umkm->category?->name,
            'marker_color' => $umkm->category?->marker_color,
            'district' => $umkm->district?->name,
            'village' => $umkm->village,
            'address' => $umkm->address,
            'latitude' => $umkm->latitude,
            'longitude' => $umkm->longitude,
            'land_area_ha' => $umkm->land_area_ha,
            'production_capacity' => $umkm->production_capacity,
            'actual_production' => $umkm->actual_production,
            'production_unit' => $umkm->production_unit,
            'production_period' => $umkm->production_period,
            'utilization_rate' => $umkm->capacity_utilization,
            'worker_count' => $umkm->worker_count,
            'transaction_value' => $umkm->transaction_value,
            'business_status' => $umkm->business_status,
            'data_source' => $umkm->data_source,
            'contact_phone' => $umkm->contact_phone,
            'updated_at' => $umkm->updated_at->format('d M Y'),
        ]);
    }
}
