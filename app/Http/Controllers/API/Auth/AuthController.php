<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
            ],Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
               'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
               'message' => $e->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request){
        $ceredentials = $request->only('email','password');
        $user = User::where('email',$request->email)->first();

        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string|max:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if (!Auth::attempt($ceredentials)) {
            if (!$user||Hash::check($request['password'], $user->password)) {
                return response()->json([
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Invalid Credentials'
                 ],Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }

            $token = $user->createToken('login_token')->plainTextToken;
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Login Success',
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],Response::HTTP_OK);
    }

    public function logout(){
        Auth::user()->tokens()->delete();
        return response()->json([
           'status' => Response::HTTP_OK,
           'message' => 'Logout success' 
        ],Response::HTTP_OK);
    }

}
