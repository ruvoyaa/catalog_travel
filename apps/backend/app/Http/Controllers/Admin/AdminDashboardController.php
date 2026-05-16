<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourEmbedding;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'tourCount' => Tour::count(),
            'publishedTourCount' => Tour::where('status', 'published')->count(),
            'categoryCount' => TourCategory::count(),
            'embeddingCount' => TourEmbedding::count(),
        ]);
    }
}
