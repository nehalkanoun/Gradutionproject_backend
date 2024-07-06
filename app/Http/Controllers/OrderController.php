<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Order=Order::all();
        return response()->json(
            ['data'=>$Order]
        );
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $input = $request->validate([
                'amount' => ['required', 'min:1', 'integer'],
                'product_ID' => ['required', 'exists:products,id'],
                'cart_ID' => ['required', 'exists:carts,id'],
            ]);
    
            $order = Order::create($input);
    
            // Retrieve the cart associated with the order
            $cart = Cart::findOrFail($input['cart_ID']);
    
            // Retrieve the product associated with the order
            $product = Product::findOrFail($input['product_ID']);
    
            // Calculate the new total price for the cart
            $newOrderTotal = $product->price * $order->amount;
            $cart->total_price += $newOrderTotal;
            $cart->save();
    
            return response()->json([
                'data' => 'created',
                'id' => $order->id
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the order.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Order=Order::findOrFail($id);
        return response()->json([
          'data'=>$Order
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
    
        $input = $request->validate([
            'amount' => ['required', 'min:1', 'integer'],
            'product_ID' => ['required', 'exists:products,id'],
            'cart_ID' => ['required', 'exists:carts,id']
        ]);
    
        // Retrieve the previous order details
        $previousOrder = $order->toArray();
    
        // Update the order
        $order->update($input);
    
        // Retrieve the cart associated with the order
        $cart = Cart::findOrFail($input['cart_ID']);
    
        // Calculate the new total price for the cart
        $cart->total_price -= ($previousOrder['amount'] * $previousOrder['product_ID']);
        $cart->total_price += ($order->amount * $order->product_ID);
        $cart->save();
    
        return response()->json([
            'data' => 'updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
    
        // Retrieve the cart associated with the order
        $cart = Cart::findOrFail($order->cart_ID);
    
        // Calculate the order total to be subtracted from the cart's total price
        $orderTotal = $order->product->price * $order->amount;
    
        // Subtract the order total from the cart's total price
        $cart->total_price -= $orderTotal;
        $cart->save();
    
        // Delete the order
        $order->delete();
    
        return response()->json([
            'data' => 'Order Deleted'
        ]);
    }

}


