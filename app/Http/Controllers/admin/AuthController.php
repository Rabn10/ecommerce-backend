<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        if (Auth::attempt($request->only('email', 'password'))) {

            $user = Auth::user();

            if ($user->role === 'admin') {
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => 200,
                    'token' => $token,
                    'id' => $user->id,
                    'name' => $user->name
                ]);
            }

            return response()->json([
                'status' => 401,
                'errors' => 'you cannot access to admin panel'
            ], 401);
        }

        return response()->json([
            'status' => 401,
            'errors' => 'Unauthorized'
        ], 401);
    }
}
