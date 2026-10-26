<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\TobaccoUmkm;

class CoordinateValidationTest extends TestCase
{
    /**
     * Uji Boundary Value Analysis (BVA) Batas Koordinat Pamekasan
     * Lintang: -7.3500000 s/d -6.9500000
     * Bujur: 103.3500000 s/d 103.6500000
     */
    public function test_boundary_values_at_exact_geographic_limits(): void
    {
        // 1. Titik Pojok Barat Daya (Min Lat, Min Lng)
        $this->assertTrue(TobaccoUmkm::isValidCoordinate(-7.3500000, 103.3500000));

        // 2. Titik Pojok Timur Laut (Max Lat, Max Lng)
        $this->assertTrue(TobaccoUmkm::isValidCoordinate(-6.9500000, 103.6500000));

        // 3. Titik Pusat Centroid Pamekasan
        $this->assertTrue(TobaccoUmkm::isValidCoordinate(-7.1610000, 103.4800000));

        // 4. BVA Di Luar Batas Lintang Selatan (-7.3500001)
        $this->assertFalse(TobaccoUmkm::isValidCoordinate(-7.3500001, 103.4800000));

        // 5. BVA Di Luar Batas Lintang Utara (-6.9499999)
        $this->assertFalse(TobaccoUmkm::isValidCoordinate(-6.9499999, 103.4800000));

        // 6. BVA Di Luar Batas Bujur Barat (103.3499999)
        $this->assertFalse(TobaccoUmkm::isValidCoordinate(-7.1610000, 103.3499999));

        // 7. BVA Di Luar Batas Bujur Timur (103.6500001)
        $this->assertFalse(TobaccoUmkm::isValidCoordinate(-7.1610000, 103.6500001));
    }

    /**
     * Uji Equivalence Partitioning (EP) Koordinat Luar Daerah & Null
     */
    public function test_equivalence_partitioning_invalid_regions(): void
    {
        // Partisi: Koordinat Surabaya (Bukan Pamekasan)
        $this->assertFalse(TobaccoUmkm::isValidCoordinate(-7.2575, 112.7521));

        // Partisi: Koordinat Jakarta
        $this->assertFalse(TobaccoUmkm::isValidCoordinate(-6.2088, 106.8456));

        // Partisi: Koordinat Bernilai Null
        $this->assertFalse(TobaccoUmkm::isValidCoordinate(null, 103.4800));
        $this->assertFalse(TobaccoUmkm::isValidCoordinate(-7.1610, null));
        $this->assertFalse(TobaccoUmkm::isValidCoordinate(null, null));
    }
}
