<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = $user->createToken('register_token')->plainTextToken;

            return response()->json([
               'status' => Response::HTTP_OK,
               'data' => $user,
               'access_token' => $token,
               'type' => 'Bearer'
            ]);
        } catch (Exception $e) {
            return response()->json([
               'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
               'message' => $e->getMessage()
            ]);
        }
    }

    public function login(){
        
    }

    public function logout(){
        
    }

}
