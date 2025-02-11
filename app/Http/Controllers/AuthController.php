<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Funcrtion used to register a new user
    public function register(Request $request)
    {
        $status = 200;

        // add vaiidation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            $this->apiData = $validator->errors();
            $this->apiMessage = $validator->errors()->first();
            $status = 400;
        } else {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // check if user was created successfully
            if ($user) {
                $this->apiValid = true;
                $this->apiMessage = "User created successfully";
            } else {
                $this->apiMessage = "Error creating new User";
                $status = 500;
            }
        }

        return response()->json([
            'valid' => $this->apiValid,
            'message' => $this->apiMessage,
            'data' => $this->apiData,
        ], $status);
    }

    // Function used to login a user
    public function login(Request $request)
    {
        $status = 200;

        // add vaiidation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            $this->apiData = $validator->errors();
            $this->apiMessage = $validator->errors()->first();
            $status = 400;
        } else {
            $token = JWTAuth::attempt($request->only('email', 'password'));

            // return error if invalid credentials
            if (!$token) {
                $this->apiMessage = "Unauthorized";
                $status = 401;
            } else {
                $this->apiValid = true;
                $this->apiMessage = "User logged in successfully";
                $this->apiData = [
                    'token' => $token,
                ];
            }
        }

        return response()->json([
            'valid' => $this->apiValid,
            'message' => $this->apiMessage,
            'data' => $this->apiData,
        ], $status);
    }
}
