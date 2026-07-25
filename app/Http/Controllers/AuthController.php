<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaksi;
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

        $user = User::where('email', $credentials['email'])->first();

        // 🛡️ Mengizinkan login untuk user dengan role 'user' ataupun 'pelanggan'
        if ($user && in_array($user->role, ['user', 'pelanggan'])) {
            if (Auth::attempt($credentials)) {
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
    // 📊 DASHBOARD MANAGEMENT (ADMIN & USER)
    // ==========================================

    public function adminDashboard(Request $request)
    {
        // Tangkap tanggal filter, jika kosong default ke tanggal hari ini
        $selectedDate = $request->input('tanggal', date('Y-m-d'));

        // Hitung pendapatan berdasarkan TANGGAL DIPILIH
        $totalPendapatan = Transaksi::whereDate('created_at', $selectedDate)
            ->where('status_pembayaran', 'Lunas')
            ->sum('total_harga');

        // Ambil SEMUA transaksi/antrean (termasuk kemarin & hari-hari sebelumnya)
        $antreanBerjalan = Transaksi::with(['user', 'layanan'])
            ->latest()
            ->get();

        return view('admin.dashboard', compact('totalPendapatan', 'antreanBerjalan', 'selectedDate'));
    }

    public function userDashboard()
    {
        // Riwayat transaksi khusus user yang sedang login
        $transaksiUser = Transaksi::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.dashboard', compact('transaksiUser'));
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