<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TobaccoUmkm;
use App\Models\District;
use App\Models\Category;
use App\Services\EconomicAnalysisService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EconomicFormulaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji formula Utilisasi Kapasitas: (Actual / Capacity) * 100%
     */
    public function test_capacity_utilization_formula_calculation(): void
    {
        $umkm = new TobaccoUmkm([
            'production_capacity' => 10000,
            'actual_production' => 8500,
        ]);

        $this->assertEquals(85.0, $umkm->capacity_utilization);

        // Jika kapasitas 0, utilisasi harus null untuk mencegah ZeroDivisionError
        $zeroCapacityUmkm = new TobaccoUmkm([
            'production_capacity' => 0,
            'actual_production' => 0,
        ]);

        $this->assertNull($zeroCapacityUmkm->capacity_utilization);
    }

    /**
     * Uji aturan kuorum validitas statistik (< 2 UMKM = Data Belum Cukup)
     */
    public function test_insufficient_data_threshold_rule(): void
    {
        // Siapkan 1 kategori dan 1 kecamatan dengan hanya 1 UMKM
        $category = Category::create([
            'name' => 'Petani Tembakau',
            'slug' => 'petani-tembakau',
            'marker_color' => '#16a34a',
        ]);

        $district = District::create([
            'code' => '3528099',
            'name' => 'Kecamatan Uji Coba',
            'latitude' => -7.1610,
            'longitude' => 103.4800,
            'area_km2' => 50,
        ]);

        TobaccoUmkm::create([
            'code' => 'TEST-001',
            'name' => 'UMKM Tunggal',
            'category_id' => $category->id,
            'district_id' => $district->id,
            'address' => 'Jl. Uji No. 1',
            'latitude' => -7.1610,
            'longitude' => 103.4800,
            'production_capacity' => 5000,
            'production_unit' => 'Kg',
            'actual_production' => 4000,
            'production_period' => '2026',
            'worker_count' => 10,
            'transaction_value' => 200000000,
            'business_status' => 'aktif',
            'data_source' => 'Pengujian',
            'is_verified' => true,
        ]);

        $service = new EconomicAnalysisService();
        $analysis = $service->getEconomicAnalysis();

        $resultDistrict = collect($analysis['rankings'])->firstWhere('district_id', $district->id);

        $this->assertNotNull($resultDistrict);
        $this->assertFalse($resultDistrict['is_sufficient']);
        $this->assertEquals('Data Belum Cukup', $resultDistrict['status_label']);
        $this->assertEquals(0, $resultDistrict['score']);
    }
}
