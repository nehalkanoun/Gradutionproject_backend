<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Customer=Customer::all();
        return response()->json(
            ['data'=>$Customer]
        );
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $input = $request->validate([
                'username' => ['required', 'unique:customers,username', 'string'],
                'password' => ['required', 'min:8'],
                'phonenumber' => ['required', 'unique:customers,phonenumber', 'min:10', 'max:10']
            ]);
    
            $customer = Customer::create($input);
    
            return response()->json([
                'data' => 'created',
                'id' => $customer->id,
                'token' => $customer->createToken('API TOKEN')->plainTextToken
            ]);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'error' => 'An error occurred while creating the customer.'
            ], 500);
        }
    }




    /**
     * Login The User
     * @param Request $request
     * @return customer
     */
    public function logincustomer(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'username' => 'required',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::guard('customer')->attempt($request->only(['username', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'username & Password does not match with our record.',
                ], 401);
            }

            $customer = customer::where('username', $request->username)->first();

            return response()->json([
                'status' => true,
                'message' => 'customer Logged In Successfully',
                'token' => $customer->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }





    public function logoutcustomer(Request $request)
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
        $Customer=Customer::findOrFail($id);
        return response()->json([
          'data'=>$Customer
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,string $id)
    {
        $Customer=Customer::findOrFail($id);
        $input=$request->validate([
            'username'=>[Rule::unique('Customers', 'username')->ignore($Customer),'string'],         
            'password'=>['min:8'],
            'phonenumber'=>[Rule::unique('Customers', 'phonenumber')->ignore($Customer),'string','min:10','max:10'],  
    ]);
    $Customer->update($input);
    return response()->json([
        'data' => 'updated'
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Customer=Customer::findOrFail($id);
        $Customer->delete();
        return response()->json([
            'data'=>'Customer Deleted'
        ]);
    }
}
