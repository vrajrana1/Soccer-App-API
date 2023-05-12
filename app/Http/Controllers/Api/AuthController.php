<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|confirmed',
                'role' => 'required|in:admin,user',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'role' => 'admin' or 'user'
            ]);

            $token = $user->createToken('myappToken')->plainTextToken;
            $response = [
                'user'  => $user,
                'token' => $token
            ];
            return response()->json([
                'message' => 'User created successfully',
                'user' => $response
            ], 201);

            } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}