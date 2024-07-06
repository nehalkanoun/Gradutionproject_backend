<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use \Illuminate\Database\QueryException;
use Illuminate\Validation\Rules\In;
class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Cart = Cart::all();
        return response()->json(
            ['data' => $Cart]
        );
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $input = $request->validate([
                'customer_ID' => ['required', 'exists:customers,id'],
            ]);

            $cart = Cart::create($input);

            // Get the orders associated with the cart
            $orders = $cart->orders;

            return response()->json([
                'data' => [
                    'cart' => $cart,
                    'orders' => $orders
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();

            return response()->json([
                'error' => 'Database error occurred: ' . $errorMessage,
                'code' => $errorCode
            ], 500);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();

            return response()->json([
                'error' => 'An unexpected error occurred: ' . $errorMessage,
                'code' => $errorCode
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cart = Cart::with(['orders.product'])->findOrFail($id);
        $orders = $cart->orders;

        $totalPrice = 0;
        $products = $orders->map(function ($order) use (&$totalPrice) {
            $productPrice = $order->product->price * $order->amount;
            $totalPrice += $productPrice;
            return [
                'id' => $order->product->id,
                'name' => $order->product->name,
                'price' => $order->product->price,
                'seller_id' => $order->product->seller_ID,
                'amount' => $order->amount,
                'total_product_price' => $productPrice
            ];
        });

        $data = [
            'cart_id' => $cart->id,
            'products' => $products,
            'total_price' => $totalPrice
        ];
        $cart->total_price = $totalPrice;
        $cart->save();
        return response()->json($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Cart = Cart::findOrFail($id);
    
        $input = $request->validate([
            'customer_ID' => [ 'exists:customers,id'],
            'status' => [ new In(['Waiting', 'Pending', 'Done'])],
        ]);
        $Cart->update($input);
        return response()->json([
            'data' => 'updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Cart = Cart::findOrFail($id);
        $Cart->delete();
        return response()->json([
            'data' => 'Cart Deleted'
        ]);
    }
}
