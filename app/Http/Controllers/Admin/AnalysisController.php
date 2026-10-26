<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EconomicAnalysisService;

class AnalysisController extends Controller
{
    public function __construct(
        protected EconomicAnalysisService $analysisService
    ) {}

    /**
     * Halaman Analisis Potensi Ekonomi UMKM Tembakau
     */
    public function index()
    {
        $analysis = $this->analysisService->getEconomicAnalysis();
        $metrics = $this->analysisService->getSummaryMetrics();
        $categoryDist = $this->analysisService->getCategoryDistribution();

        return view('admin.analysis.index', compact('analysis', 'metrics', 'categoryDist'));
    }
}
