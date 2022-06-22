<?php

namespace App\Http\Controllers\Vender;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function getLogin()
    {
        return view('vender.login');
    }   

    public function vendorLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password) && $user->hasRole(UserType::getTypeName(UserType::VENDOR))) {
                $token = $user->createToken('authToken')->plainTextToken;

                auth()->login($user);

                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'status' => 'success',
                    'message' => 'Login successful',
                ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }
    }

    public function dashboard()
    {
        return view('vender.dashboard');
    }
}
