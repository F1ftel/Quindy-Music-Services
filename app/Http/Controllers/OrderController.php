<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Package;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create()
    {
        $services = Service::all();
        $packages = Package::all();

        return view('order.create', compact('services', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'google_drive_link' => 'required|url',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'package_id' => $request->package_id,
            'google_drive_link' => $request->google_drive_link,
            'status' => 'pending',
        ]);

        if ($request->has('services')) {
            $order->services()->attach($request->services);
        }

        return redirect('/dashboard')->with('success', 'Order placed successfully!');
    }

    public function dashboard()
    {
        $orders = Order::where('user_id', Auth::id())->with('package', 'services')->get();

        return view('dashboard', compact('orders'));
    }

    public function index()
    {
        $orders = \App\Models\Order::where('user_id', auth()->id())->get();
        return view('orders.index', compact('orders'));
    }
}
