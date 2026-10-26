<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\District;
use App\Models\Category;
use App\Models\TobaccoUmkm;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MapFilterTest extends TestCase
{
    use RefreshDatabase;

    protected District $pamekasan;
    protected District $kadur;
    protected Category $categoryPetani;
    protected Category $categorySkt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pamekasan = District::create([
            'code' => '3528040',
            'name' => 'Pamekasan',
            'latitude' => -7.1610,
            'longitude' => 103.4800,
            'area_km2' => 26.47,
        ]);

        $this->kadur = District::create([
            'code' => '3528100',
            'name' => 'Kadur',
            'latitude' => -7.0950,
            'longitude' => 103.5500,
            'area_km2' => 52.33,
        ]);

        $this->categoryPetani = Category::create([
            'name' => 'Petani & Pembibitan Tembakau',
            'slug' => 'petani-pembibitan-tembakau',
            'marker_color' => '#16a34a',
        ]);

        $this->categorySkt = Category::create([
            'name' => 'Produsen Sigaret Kretek Tangan (SKT)',
            'slug' => 'produsen-sigaret-kretek-tangan-skt',
            'marker_color' => '#dc2626',
        ]);

        TobaccoUmkm::create([
            'code' => 'MAP-001',
            'name' => 'Petani Subur Kadur',
            'category_id' => $this->categoryPetani->id,
            'district_id' => $this->kadur->id,
            'address' => 'Desa Kadur',
            'latitude' => -7.0950,
            'longitude' => 103.5500,
            'production_capacity' => 8000,
            'production_unit' => 'Kg',
            'actual_production' => 7500,
            'production_period' => '2026',
            'business_status' => 'aktif',
            'data_source' => 'Test',
            'is_verified' => true,
        ]);

        TobaccoUmkm::create([
            'code' => 'MAP-002',
            'name' => 'PR Kretek Kota Pamekasan',
            'category_id' => $this->categorySkt->id,
            'district_id' => $this->pamekasan->id,
            'address' => 'Jl. Trunojoyo',
            'latitude' => -7.1610,
            'longitude' => 103.4800,
            'production_capacity' => 500000,
            'production_unit' => 'Batang',
            'actual_production' => 450000,
            'production_period' => '2026',
            'business_status' => 'aktif',
            'data_source' => 'Test',
            'is_verified' => true,
        ]);
    }

    public function test_geojson_umkm_endpoint_returns_feature_collection(): void
    {
        $response = $this->getJson('/api/geojson/umkms');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'type',
                'features' => [
                    '*' => [
                        'type',
                        'id',
                        'geometry' => ['type', 'coordinates'],
                        'properties' => ['id', 'code', 'name', 'category_name', 'district_name'],
                    ]
                ],
                'metadata' => ['total_features'],
            ]);

        $this->assertEquals('FeatureCollection', $response->json('type'));
        $this->assertEquals(2, $response->json('metadata.total_features'));
    }

    public function test_filter_geojson_by_district(): void
    {
        $response = $this->getJson("/api/geojson/umkms?district_id={$this->kadur->id}");

        $response->assertStatus(200);
        $features = $response->json('features');

        $this->assertCount(1, $features);
        $this->assertEquals('MAP-001', $features[0]['properties']['code']);
    }

    public function test_filter_geojson_by_category(): void
    {
        $response = $this->getJson("/api/geojson/umkms?category_id={$this->categorySkt->id}");

        $response->assertStatus(200);
        $features = $response->json('features');

        $this->assertCount(1, $features);
        $this->assertEquals('MAP-002', $features[0]['properties']['code']);
    }

    public function test_filter_geojson_by_search_query(): void
    {
        $response = $this->getJson('/api/geojson/umkms?q=Kretek');

        $response->assertStatus(200);
        $features = $response->json('features');

        $this->assertCount(1, $features);
        $this->assertEquals('PR Kretek Kota Pamekasan', $features[0]['properties']['name']);
    }

    public function test_geojson_districts_endpoint_returns_boundaries(): void
    {
        $response = $this->getJson('/api/geojson/districts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'type',
                'features',
                'metadata' => ['region', 'total_districts'],
            ]);

        $this->assertEquals(2, $response->json('metadata.total_districts'));
    }
}
