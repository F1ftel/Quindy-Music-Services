<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Package;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('id', 'desc')->get();
        return view('portfolio.index', compact('portfolios'));
    }

    public function show(Portfolio $portfolio)
    {
        return view('portfolio.show', compact('portfolio'));
    }

    public function create()
    {
        $services = Service::all();
        $packages = Package::all();
        return view('portfolio.create', compact('services', 'packages'));
    }

    public function store(Request $request)
    {
        $itemData = explode('-', $request->input('item_id'));
        $type = $itemData[0];
        $id = $itemData[1] ?? null;

        $data = $request->validate([
            'track_link' => 'required|url',
            'review' => 'nullable|string',
        ]);

        if ($type === 'App\Models\Service') {
            $data['service_id'] = $id;
            $data['package_id'] = null;
        } elseif ($type === 'App\Models\Package') {
            $data['package_id'] = $id;
            $data['service_id'] = null;
        }

        Portfolio::create($data);

        return redirect()->route('portfolio.index')->with('success', 'Project added.');
    }
}
