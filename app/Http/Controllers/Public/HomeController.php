<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\EconomicAnalysisService;
use App\Models\TobaccoUmkm;
use App\Models\District;
use App\Models\Category;

class HomeController extends Controller
{
    public function __construct(
        protected EconomicAnalysisService $analysisService
    ) {}

    /**
     * Dashboard Publik WebGIS UMKM Tembakau Pamekasan
     */
    public function index()
    {
        $metrics = $this->analysisService->getSummaryMetrics();
        $districts = $this->analysisService->getDistrictDistribution();
        $categories = $this->analysisService->getCategoryDistribution();

        // 6 UMKM terbaru terverifikasi
        $latestUmkms = TobaccoUmkm::with(['district', 'category'])
            ->where('is_verified', true)
            ->latest()
            ->take(6)
            ->get();

        return view('public.home', compact('metrics', 'districts', 'categories', 'latestUmkms'));
    }
}
