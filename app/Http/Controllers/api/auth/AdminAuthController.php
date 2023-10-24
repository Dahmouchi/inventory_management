<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{

    public function index(Request $request){

        $admin = Auth::guard('admin')->user();

        if($admin){
            return response()->json([
                'message' => 'Admin fetched successfully',
                'admin' => $admin
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function login(Request $request){

        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);


        if(Auth::guard('admin')->attempt($data)){

            // create token
            $admin = Auth::guard('admin')->user();

            $token = $admin->createToken('admin-token', ['admin']);

            return response()->json([
                'message' => 'Admin logged in successfully',
                'admin' => $admin,
                'token' => $token
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);

    }

    public function logout(Request $request ){

        $admin = Auth::guard('sanctum')->user();


        if($admin){
            $admin->tokens()->delete();
            return response()->json([
                'message' => 'Admin logged out successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);


    }

    public function register(Request $request){

        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:admins',
            'password' => 'required|string',
            'phone' => 'required|string',
        ]);

        $data['password'] =  Hash::make($data['password']);

        $admin = Admin::create($data);

        if($admin){
            return response()->json([
                'message' => 'Admin created successfully',
                'admin' => $admin
            ], 201);
        }

        return response()->json([
            'message' => 'Error creating admin'
        ], 400);
    }



    public function resetPassword(Request $request){



    }


}
