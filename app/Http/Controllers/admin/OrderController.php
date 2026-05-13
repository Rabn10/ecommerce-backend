<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 200,
            'data' => $orders
        ]);
    }

    public function detail($id)
    {
        $order = Order::with('items', 'items.product')->find($id);

        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Order not found'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $order
        ]);
    }
}
