<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class AdminController extends Controller
{
    public function index()
    {
         $admin=admin::all();
        return response()->json([
         'data'=>$admin
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input=$request->validate([
            'username'=>['required','unique:admins,username','string'],
            'password'=>['required','min:8'],
            
        ]);
        admin::create($input);
        return response()->json([
        'data'=>'created'
         ]) ;}



         /**
     * Login The User
     * @param Request $request
     * @return admin
     */
    public function loginadmin(Request $request)
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

            if(!Auth::guard('admin')->attempt($request->only(['username', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'username & Password does not match with our record.',
                ], 401);
            }

            $admin = admin::where('username', $request->username)->first();
            $authtoken = $admin->createToken("auth-token")->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'admin Logged In Successfully',
                'access_token'=>$authtoken
                
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                
            ], 500);
        }
    }
    public function logoutadmin(Request $request)
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
            // Handle any errors that may occur during the logout process
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
      $admin=admin::findOrFail($id);
      return response()->json([
        'data'=>$admin
      ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin=admin::findOrFail($id);
        $input=$request->validate([
            'username'=>[Rule::unique('admins', 'username')->ignore($admin),'string'],         
            'password'=>['min:8'],
            
    ]);
    $admin->update($input);
    return response()->json([
        'data' => 'updated'
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin=admin::findOrFail($id);
        $admin->delete();
        return response()->json([
            'data'=>'Admin Deleted'
        ]);
    }
}
