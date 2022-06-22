<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function getRegister()
    {
        $roles = Role::all();
        return view('user.register', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole(UserType::getTypeName($request->role_id));

        if ($user) {
            return response()->json([
                'data' => $user,
                'status' => 'success',
                'message' => 'Register successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
            ], 401);
        }
    }
}
