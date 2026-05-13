<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cek user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // Cek password (MD5 legacy support + Hash support)
        $passwordOk = false;
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $passwordOk = true; // bcrypt
            } elseif (md5($request->password) === $user->password) {
                $passwordOk = true; // MD5 legacy
            }
        }

        if ($user && $passwordOk) {
            Auth::login($user);
            return $this->redirectByRole($user);
        }

        return back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole(User $user)
    {
        if (strtoupper($user->role) === 'CEO') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin2.dashboard');
    }
}
