<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get orders only for the currently logged-in user
        $orders = Order::where('user_id', Auth::id())->get();

        return view('dashboard.index', compact('orders'));
    }
}
