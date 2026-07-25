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

        // 🛡️ Mengizinkan login untuk user dengan role 'user' ataupun 'pelanggan'
        $user = User::where('email', $credentials['email'])->first();

        if ($user && in_array($user->role, ['user', 'pelanggan'])) {
            if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
                $request->session()->regenerate();
                return redirect()->route('user.dashboard'); 
            }
        }

        // 🔍 Deteksi Admin salah kamar
        if ($user && $user->role === 'admin') {
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
        // Mengecek apakah view berada di auth.admin-login atau admin.login
        if (view()->exists('auth.admin-login')) {
            return view('auth.admin-login');
        }
        return view('admin.login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->role === 'admin') {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
        }

        // 🔍 Deteksi Pelanggan/User biasa mencoba login dari portal Admin
        if ($user && in_array($user->role, ['user', 'pelanggan'])) {
            return back()->withErrors([
                'email' => 'Akun Pelanggan terdeteksi! Silakan login melalui halaman utama.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau password Admin salah.',
        ])->onlyInput('email');
    }

    public function showAdminRegister()
    {
        if (view()->exists('auth.admin-register')) {
            return view('auth.admin-register');
        }
        return view('admin.register');
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
        $baseUsername     = !empty($cleanEmailPrefix) ? $cleanEmailPrefix : 'user';
        $username         = $baseUsername;
        $counter          = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}