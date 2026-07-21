<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 🛡️ Kunci gerbang utama pelanggan: hanya boleh role 'user'
        if (Auth::attempt(array_merge($credentials, ['role' => 'user']))) {
            $request->session()->regenerate();
            return redirect()->route('user.dashboard'); 
        }

        // 🔍 Deteksi Admin salah kamar: Berikan peringatan tegas!
        if (Auth::validate(array_merge($credentials, ['role' => 'admin']))) {
            return back()->withErrors([
                'email' => 'Akun Admin terdeteksi! Demi keamanan, silakan masuk melalui halaman Login Internal Admin.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register'); 
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 💡 Ambil prefix email dan bersihkan karakter khusus agar jadi username valid
        $cleanEmailPrefix = Str::slug(explode('@', $request->email)[0], '');
        $baseUsername     = $cleanEmailPrefix ?: 'user';
        $username         = $baseUsername;
        $counter          = 1;

        // Cek keunikan username di PostgreSQL
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        User::create([
            'name'     => $request->name,
            'username' => $username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user', 
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('landing');
    }
}