<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Package;
use App\Models\Portfolio;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        $featuredServices = Service::take(3)->get();
        $featuredPackages = Package::take(2)->get();

        $latestPortfolios = Portfolio::with(['service', 'package'])
            ->orderByDesc('id')
            ->take(3)
            ->get();

        return view('home', compact('featuredServices', 'featuredPackages', 'latestPortfolios'));
    }
}
