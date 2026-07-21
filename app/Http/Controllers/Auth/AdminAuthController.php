<?php

namespace App\Http\Controllers\Auth; // ◄ SESUAIKAN: Berada di subfolder Auth

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        // 📁 Diarahkan langsung ke resources/views/admin/login.blade.php
        return view('admin.login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 🛡️ Mengunci autentikasi khusus role admin
        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Admin!');
        }

        return back()->withErrors([
            'email' => 'Kredensial login admin tidak cocok atau Anda bukan pengelola.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        // 📁 Diarahkan langsung ke resources/views/admin/register.blade.php
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'admin',
            'password' => Hash::make($request->password),
        ]);

        Auth::login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Akun Admin Baru Berhasil Didaftarkan!');
    }
}