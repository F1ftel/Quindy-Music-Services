<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'package')->get();

        $pending = Order::where('status', 'pending')->count();
        $in_progress = Order::where('status', 'in_progress')->count();
        $completed = Order::where('status', 'completed')->count();

        return view('admin.orders.index', compact('orders', 'pending', 'in_progress', 'completed'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $order = Order::findOrFail($orderId);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
