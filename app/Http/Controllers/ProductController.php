<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Product = Product::all();
        foreach ($Product as $product) {
            $seller = Seller::findOrFail($product->seller_ID);
            $username = $seller->username;
            $product['username'] = $username;
        }
        return response()->json(
            ['data' => $Product]
        );
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $input = $request->validate([
                'name' => ['required'],
                'price' => ['required', 'min:1', 'integer'],
                'seller_ID' => ['required', 'exists:sellers,id'],
            ]);

            $product = Product::create($input);

            return response()->json([
                'data' => 'created',
                'id' => $product->id
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the product.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Product = Product::findOrFail($id);
        return response()->json([
            'data' => $Product
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Product = Product::findOrFail($id);
        $input = $request->validate([
            'name' => ['string'],
            'price' => ['min:1', 'integer'],
            'seller_ID' => ['string', 'exists:sellers,id'],


        ]);
        $Product->update($input);
        return response()->json([
            'data' => 'updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Product = Product::findOrFail($id);
        $Product->delete();
        return response()->json([
            'data' => 'Product Deleted'
        ]);
    }

    public function randomProducts()
    {

        $randomProducts2 = Product::inRandomOrder()->limit(10)->get();
        foreach ($randomProducts2 as $product) {
            $seller = Seller::findOrFail($product->seller_ID);
            $username = $seller->username;
            $product['username'] = $username;
        }
        return response()->json([
            'data' => $randomProducts2,

        ]);
    }
    public function getproduct (string $id)
    {
        $products = Product::where(['seller_ID' => $id])->get();
        return response()->json([
            'data' => $products,
        ]);
    }
}
