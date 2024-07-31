<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
// use Laravel\Sanctum\NewAccessToken\

class UserController extends Controller
{
    //
    /**
     * Summary of createUser
     * @param \Illuminate\Http\Request $req
     * @param Request $req 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createUser(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $user = User::create([
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make($req->password),
            ]);

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
                'token' => $user->createToken('authToken')->plainTextToken,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to create user',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function loginUser(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            // if (!Auth::attempt($req->only(['email', 'password']))) {
            //     return response()->json(['error' => 'Unauthorized'], 401);
            // }

            // if (!$token = auth('api')->attempt(['email' => $req->email, 'password' => $req->password])) {
            //     return response()->json(['error' => 'Unauthorized'], 401);
            // }

            $user = User::where('email', $req->email)->first();

            if (!empty($user)) {
                if (Hash::check($req->password, $user->password)) {
                    $token = $user->createToken("API TOKEN")->plainTextToken;
                    return response()->json([
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'user' => $user,
                        // 'token' => $user->createToken("API TOKEN")->plainTextToken()
                    ], 200);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to login user',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}