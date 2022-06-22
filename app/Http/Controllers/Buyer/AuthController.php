<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserType;

class AuthController extends Controller
{
    public function getLogin()
    {
        return view('buyer.login');
    }

    public function buyerLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password) && $user->hasRole(UserType::getTypeName(UserType::BUYER))) {
            $token = $user->createToken('authToken')->plainTextToken;
            auth()->login($user);

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'status' => 'success',
                'message' => 'Login successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }
    }
}
