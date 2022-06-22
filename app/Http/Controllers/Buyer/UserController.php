<?php

namespace App\Http\Controllers\Buyer;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartProduct = auth()->user()->cart->where('product_id', $request->product_id)->first();

        if ($cartProduct) {
            $cartProduct->quantity += $request->quantity;
            $cartProduct->save();
        } else {
            auth()->user()->cart()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'user_id' => auth()->user()->id,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully',
        ], 201);
    }

    public function noOfCartItems()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'No of cart items',
            'data' => auth()->user()->cart->count(),
        ], 200);
    }

    public function placeOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            $order = auth()->user()->orders()->create([
                'total_amount' => 0,
                'status' => Status::PENDING,
                'user_id' => auth()->user()->id,
            ]);

            $total_amount = 0;
            $data = [];

            foreach (auth()->user()->cart as $cart) {
                $product = $cart->product;
                $unit_price = $product->unit_price;
                $total_amount = $total_amount + ($unit_price * $cart->quantity);

                $product->stock -= $cart->quantity;
                $product->save();

                $data[] = [
                    'quantity' => $cart->quantity,
                    'amount' => $cart->product->unit_price * $cart->quantity,
                    'product_id' => $cart->product->id,
                    'order_id' => $order->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $order->orderItems()->insert($data);

            $order->update([
                'total_amount' => $total_amount,
            ]);

            // clear cart
            auth()->user()->cart()->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order placed successfully',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error occured while placing order',
            ], 500);
        }
    }
}
