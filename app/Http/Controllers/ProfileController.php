<?php

namespace App\Http\Controllers;

use App\Models\ToDoList;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }
    public function index(): \Illuminate\Contracts\View\Factory|
                             \Illuminate\Contracts\View\View|
                             \Illuminate\Contracts\Foundation\Application
    {
        $totalCounts = $this->service->getCounts();
        $chartData = $this->service->getChartData();
        return view('profile', compact('totalCounts', 'chartData'));
    }
}
