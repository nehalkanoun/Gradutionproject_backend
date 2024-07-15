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
    
           
            $cart = Cart::findOrFail($input['cart_ID']);
    
            
            $product = Product::findOrFail($input['product_ID']);
    
           
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
    
       
        $previousOrder = $order->toArray();
    
       
        $order->update($input);
    
    
        $cart = Cart::findOrFail($input['cart_ID']);
    
      
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
    
        
        $cart = Cart::findOrFail($order->cart_ID);
    
        
        $orderTotal = $order->product->price * $order->amount;
    
      
        $cart->total_price -= $orderTotal;
        $cart->save();
    
    
        $order->delete();
    
        return response()->json([
            'data' => 'Order Deleted'
        ]);
    }

}


