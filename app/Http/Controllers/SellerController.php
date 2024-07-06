<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Seller = Seller::all();
        return response()->json(
            ['data' => $Seller]
        );
    }



    /**
     * Store a newly created resource in storage.
     */

     
    public function store(Request $request)
    {
        try {
            $input = $request->validate([
                'username' => ['required', 'unique:sellers,username', 'string'],
                'password' => ['required', 'min:8'],
                'phonenumber' => ['required', 'unique:sellers,phonenumber', 'min:10', 'max:10'],
                'location'=>['string'],
                'details'=>['string']
            ]);
    
            $Seller = Seller::create($input);
    
            return response()->json([
                'data' => 'created',
                'id' => $Seller->id,
                'token' => $Seller->createToken('API TOKEN')->plainTextToken
            ]);
        } catch (ValidationException $e) {
          
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
         
            return response()->json([
                'error' =>$e
            ], 500);
        }
    }




    /**
     * Login The User
     * @param Request $request
     * @return seller
     */
    public function loginseller(Request $request)
    {
        try {
            $validateseller = Validator::make($request->all(), 
            [
                'username' => 'required',
                'password' => 'required'
            ]);

            if($validateseller->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateseller->errors()
                ], 401);
            }

            if(!Auth::guard('seller')->attempt($request->only(['username', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'username & Password does not match with our record.',
                ], 401);
            }

            $seller = seller::where('username', $request->username)->first();

            return response()->json([
                'status' => true,
                'message' => 'seller Logged In Successfully',
                'token' => $seller->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
   

     public function logoutseller(Request $request)
     {
         try {
             // Get the token from the request body
             $token = $request->input('token');
     
    
             $tokenWithoutId = substr($token, strpos($token, '|') + 1);
     
             // Find the token in the database and delete it
             $deletedRows = DB::table('personal_access_tokens')
                 ->where('token', hash('sha256', $tokenWithoutId))
                 ->delete();
     
             if ($deletedRows > 0) {
                 return response()->json([
                     'success' => true,
                     'message' => 'تم تسجيل الخروج بنجاح',
                     'token' => $tokenWithoutId
                 ]);
             } else {
                 return response()->json([
                     'success' => false,
                     'message' => 'المستخدم غير مصادق',
                     'token' => $tokenWithoutId
                 ], 401);
             }
         } catch (\Exception $e) {
            
             Log::error('Error during logout process: ' . $e->getMessage());
             return response()->json([
                 'success' => false,
                 'message' => 'حدث خطأ أثناء عملية تسجيل الخروج',
                 'error' => $e->getMessage(),
                 'token' => $tokenWithoutId
             ], 500);
         }
     }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Seller = Seller::findOrFail($id);
        return response()->json([
            'data' => $Seller
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Seller = Seller::findOrFail($id);
        $input = $request->validate([
            'username' => [Rule::unique('sellers', 'username')->ignore($Seller), 'string'],
            'password' => ['min:8'],
            'phonenumber' => [Rule::unique('sellers', 'phonenumber')->ignore($Seller), 'string', 'min:10', 'max:10'],
            'location' => [ 'required'],
            'details' => ['required']
        ]);
        $Seller->update($input);
        return response()->json([
            'data' => 'updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id)
    {
        $Seller = Seller::findOrFail($id);
        $Seller->delete();
        return response()->json([
            'data' => 'Seller Deleted'
        ]);
    }
    public function showorders(string $id)
{
    $pendingOrders = DB::table('carts')
        ->where('seller_id', $id)
        ->where('status', 'pending')
        ->get();

    return response()->json([
        'data' => $pendingOrders
    ]);
}
}
