<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ==========================================
    // 👤 ALUR AUTHENTICATION USER (PELANGGAN)
    // ==========================================

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

        // 🔍 Deteksi Admin salah kamar: Berikan peringatan
        if (Auth::validate(array_merge($credentials, ['role' => 'admin']))) {
            return back()->withErrors([
                'email' => 'Akun Admin terdeteksi! Silakan masuk melalui halaman Login Admin.',
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

        // 💡 Generate username otomatis untuk role 'user'
        $username = $this->generateUniqueUsername($request->email);

        User::create([
            'name'     => $request->name,
            'username' => $username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user', 
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ==========================================
    // 🛡️ ALUR AUTHENTICATION ADMIN
    // ==========================================

    public function showAdminLogin()
    {
        return view('auth.admin-login'); // Sesuaikan nama view login admin kamu
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password Admin salah.',
        ])->onlyInput('email');
    }

    public function showAdminRegister()
    {
        return view('auth.admin-register'); // Sesuaikan nama view register admin kamu
    }

    public function adminRegister(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 💡 Generate username otomatis untuk role 'admin'
        $username = $this->generateUniqueUsername($request->email);

        $admin = User::create([
            'name'     => $request->name,
            'username' => $username,
            'email'    => $request->email,
            'role'     => 'admin',
            'password' => Hash::make($request->password),
        ]);

        Auth::login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Akun Admin Baru Berhasil Didaftarkan!');
    }

    // ==========================================
    // 🚪 LOGOUT & HELPER
    // ==========================================

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    /**
     * Helper privat untuk membuat username unik otomatis dari email
     */
    private function generateUniqueUsername(string $email): string
    {
        $cleanEmailPrefix = Str::slug(explode('@', $email)[0], '');
        $baseUsername     = $cleanEmailPrefix ?: 'user';
        $username         = $baseUsername;
        $counter          = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}