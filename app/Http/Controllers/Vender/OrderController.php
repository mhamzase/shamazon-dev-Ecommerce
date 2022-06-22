<?php

namespace App\Http\Controllers\Vender;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = auth()->user()->products->map(function ($product) {
            return $product->orderItems;
        })->flatten()->groupBy('order_id')->map(function ($order) {
            return $order;
        })->sortByDesc('created_at');

        return view('vender.orders.index', compact('orders'));
    }

    /**
     * Show the specified resource details
     *
     * @return \Illuminate\Http\Response
     */

    public function orderDetails($id)
    {
        $order = Order::findOrFail($id);

        $orderItems = $order->with(['orderItems' => function ($query) {
            $query->with(['product' => function ($query) {
                $query->where('user_id', auth()->id());
            }]);
        }])->get();

        return response()->json([
            'status' => 'success',
            'data' => $orderItems,
        ], 200);
    }
}
