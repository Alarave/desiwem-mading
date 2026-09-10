<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Login Berhasil',
                    'user' => Auth::user(),
                ]);
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Username atau password salah',
            ], 401);
        }

        throw ValidationException::withMessages([
            'username' => 'Username atau password yang dimasukkan salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Logout Berhasil']);
        }

        return redirect()->route('login');
    }

    public function me()
    {
        return response()->json([
            'user' => Auth::user(),
        ]);
    }
}
