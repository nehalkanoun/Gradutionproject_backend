<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Customer;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Order = Order::all();
        return response()->json(
            ['data' => $Order]
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
        $Order = Order::findOrFail($id);
        return response()->json([
            'data' => $Order
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
    public function adminupdateorder(Request $request, string $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'amount' => 'required|integer|min:1', 
        ]);
        try {
            // Find the order by ID
            $order = Order::findOrFail($id);
            // Update the order details
            $order->amount = $validatedData['amount'];
            $previousOrder = $order->toArray();
            $order->update($validatedData);
            $cart = Cart::findOrFail($order['cart_ID']);
            $cart->total_price -= ($previousOrder['amount'] * $previousOrder['product_ID']);
            $cart->total_price += ($order->amount * $order->product_ID);
            $cart->save();
    
            // Save the updated order
            $order->save();
    
            return response()->json([
                'data' => 'Order updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error updating order: ' . $e->getMessage()
            ], 500);
        }
    }
    


    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id)
    {
    $order = Order::findOrFail($id);
    $order->delete();
    return response()->json([
    'data' => 'order Deleted'
    ]);
    }
    public function getsellerorders(string $id)
    {
        try {
            // Retrieve all products associated with the seller ID
            $products = Product::where('seller_ID', $id)->get();

            if ($products->isEmpty()) {
                return response()->json(['message' => 'No products found for this seller.'], 404);
            }

            $allOrders = [];
            foreach ($products as $product) {
                // Retrieve all orders for each product
                $orders = Order::where('product_ID', $product->id)->get();

                foreach ($orders as $order) {
                    try {
                        // Retrieve product details
                        $productDetails = Product::findOrFail($order->product_ID);
                        $order['productname'] = $productDetails->name;

                        // Calculate total price for the order
                        $order['total_product_price'] = $order->amount * $productDetails->price;

                        // Retrieve cart details
                        $cart = Cart::findOrFail($order->cart_ID);

                        // Add total price of the cart to the order
                        $order['cart_total_price'] = $cart->total_price ?? 'Total price not available';

                        // Retrieve customer details
                        $customer = Customer::findOrFail($cart->customer_ID);
                        $order['customername'] = $customer->username;
                        $order['phonenumber'] = $customer->phonenumber;

                        // Add order to the response array
                        $allOrders[] = $order;
                    } catch (\Exception $e) {
                        // Handle individual order processing errors
                        return response()->json(['message' => 'Error processing order: ' . $e->getMessage()], 500);
                    }
                }
            }

            return response()->json([
                'sellerproducts' => $allOrders,
            ]);
        } catch (\Exception $e) {
            // Handle overall process errors
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function getallorders()
    {
        try {
            $orders = Order::with([
                'product' => function ($query) {
                    $query->select('id', 'name', 'price', 'seller_id')
                        ->with('seller:id,username');
                },
                'cart' => function ($query) {
                    $query->select('id', 'customer_id')
                        ->with('customer:id,name,phonenumber');
                }
            ])->get();

            $orders = $orders->map(function ($order) {
                try {
                    $product = $order->product;

                    $seller = Seller::findOrFail($product->seller_id);
                    $cart = $order->cart;
                    $cart = Cart::findOrFail($order->cart_ID);
                    $customer = Customer::findOrFail($cart->customer_ID);

                    return [
                        'orderid' => $order->id,
                        'productname' => $product ? $product->name : 'Product not found',
                        'productprice' => $product ? $product->price : 'Price not available',
                        'sellerusername' => $seller ? $seller->username : 'Seller not found',
                        'customername' => $customer ? $customer->username : 'Customer not found',
                        'customerphone' => $customer ? $customer->phonenumber : 'Phone not available',
                        'amount' => $order->amount,
                        'total_product_price' => $product ? $order->amount * $product->price : 'Total price not available',
                  
                    ];
                } catch (\Exception $e) {
                    return [
                        'orderid' => $order->id,
                        'error' => 'Error processing order: ' . $e->getMessage(),
                    ];
                }
            });

            return response()->json(['data' => $orders]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching orders: ' . $e->getMessage()], 500);
        }
    }
}