<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        //Validate info
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        //Check validations
        if ($validator->fails()) {
            throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
        }

        //Create user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    // Login user and get token
    public function login(Request $request)
    {
        //Validate info
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //Check validations
        if ($validator->fails()) {
            throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
        }

        //Validate and create token
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'message' => 'User logged in successfully',
                'token' => $token
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Logout user
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
