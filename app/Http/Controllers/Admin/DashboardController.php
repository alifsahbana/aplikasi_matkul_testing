<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EconomicAnalysisService;
use App\Models\TobaccoUmkm;
use App\Models\ImportLog;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct(
        protected EconomicAnalysisService $analysisService
    ) {}

    /**
     * Dashboard Manajemen Administrator
     */
    public function index()
    {
        $metrics = $this->analysisService->getSummaryMetrics();
        $districts = $this->analysisService->getDistrictDistribution();
        $categories = $this->analysisService->getCategoryDistribution();

        $recentImports = ImportLog::with('user')->latest()->take(5)->get();
        $recentUmkms = TobaccoUmkm::with(['district', 'category'])->latest()->take(8)->get();
        $userCount = User::count();

        return view('admin.dashboard', compact(
            'metrics',
            'districts',
            'categories',
            'recentImports',
            'recentUmkms',
            'userCount'
        ));
    }
}
