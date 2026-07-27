<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function landingPage()
    {
        return view('landing');
    }

    /**
     * Fitur Akses Cepat: Cek Progres Cucian Tanpa Login
     */
    public function cekProgresCucian(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'nomor_nota'      => 'required|string|max:50',
        ]);

        $nama = trim($request->input('nama_pelanggan'));
        $nota = trim($request->input('nomor_nota'));

        $hasilTransaksi = Transaksi::with(['user', 'layanan'])
            ->where(function ($q) use ($nota) {
                $q->where('no_nota', $nota)
                  ->orWhere('id_transaksi', $nota);
            })
            ->whereHas('user', function ($query) use ($nama) {
                $query->where('name', 'like', '%' . $nama . '%');
            })
            ->first();

        if (!$hasilTransaksi) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data cucian tidak ditemukan. Pastikan Nama dan Nomor Nota Anda sesuai.');
        }

        return view('landing', [
            'cucian'         => $hasilTransaksi,
            'nama_pelanggan' => $nama,
            'nomor_nota'      => $nota,
        ]);
    }

    public function adminDashboard(Request $request)
    {
        // 1. Tangkap tanggal filter (default hari ini)
        $selectedDate = $request->input('tanggal', now()->format('Y-m-d'));

        // 2. Total pendapatan hari/tanggal terpilih (Optimasi query date)
        $totalPendapatanHariIni = Transaksi::whereDate('tanggal', $selectedDate)
            ->where('status_pembayaran', 'Lunas')
            ->sum('total_harga');

        // 3. Menampilkan antrean & data pendukung
        $transaksi = Transaksi::with(['user', 'layanan'])
            ->orderBy('id_transaksi', 'desc')
            ->get();

        $layanan = Layanan::all();

        $pelanggan = User::whereIn('role', ['user', 'pelanggan'])
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalPendapatanHariIni',
            'transaksi',
            'layanan',
            'pelanggan',
            'selectedDate'
        ));
    }

    public function userDashboard()
    {
        $layananPilihan = Layanan::all();

        $transaksi = Transaksi::with('layanan')
            ->where('user_id', auth()->id())
            ->orderBy('id_transaksi', 'desc')
            ->get();

        return view('user.dashboard', compact('transaksi', 'layananPilihan'));
    }

    public function storeTransaksi(Request $request)
    {
        $request->validate([
            'user_id'           => 'nullable|exists:users,id',
            'id_layanan'        => 'required|exists:layanan,id_layanan',
            'quantity'          => 'required|numeric|min:0.1',
            'status_pembayaran' => 'required|string',
            'metode_pembayaran' => 'required|string',
        ]);

        // Gunakan Database Transaction untuk mencegah race condition / nota duplikat
        DB::transaction(function () use ($request) {
            $layanan = Layanan::findOrFail($request->id_layanan);
            $totalHarga = $layanan->harga_satuan * $request->quantity;

            // Proteksi: Jika bukan admin, paksa user_id milik user yang sedang login
            $userId = auth()->user()->role === 'admin' && $request->filled('user_id')
                ? $request->user_id
                : auth()->id();

            // Otomatisasi Nomor Nota dengan Lock untuk keamanan concurrency
            $hariIni = now()->format('Ymd');
            $transaksiTerakhirHariIni = Transaksi::whereDate('created_at', now()->today())
                ->lockForUpdate()
                ->orderBy('id_transaksi', 'desc')
                ->first();

            $urutan = 1;
            if ($transaksiTerakhirHariIni && $transaksiTerakhirHariIni->no_nota) {
                $parts = explode('-', $transaksiTerakhirHariIni->no_nota);
                $urutanTerakhir = (int) end($parts);
                $urutan = $urutanTerakhir + 1;
            }

            $noNota = 'INV-' . $hariIni . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

            Transaksi::create([
                'user_id'           => $userId,
                'id_layanan'        => $request->id_layanan,
                'quantity'          => $request->quantity,
                'total_harga'       => $totalHarga,
                'status_pembayaran' => $request->status_pembayaran,
                'metode_pembayaran' => ucfirst(strtolower($request->metode_pembayaran)),
                'status_cucian'     => 'Antrean',
                'no_nota'           => $noNota,
                'tanggal'           => now()->format('Y-m-d'),
            ]);
        });

        return redirect()->back()->with('success', 'Transaksi Laundry Baru Berhasil Disimpan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Antrean,Diproses/Dicuci,Disetrika,Selesai & Siap Diambil,Sudah Diambil',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status_cucian = $request->status;
        $transaksi->save();

        return back()->with('success', 'Status cucian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return back()->with('success', 'Data orderan berhasil dihapus dari sistem.');
    }

    /**
     * Menyimpan Data Pelanggan Baru (Offline/Walk-in)
     */
    public function storePelanggan(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
        ]);

        $pelanggan = User::create([
            'name'     => $request->name,
            'whatsapp' => $request->whatsapp,
            'role'     => 'user',
            'email'    => 'offline_' . time() . rand(10, 99) . '@cleanclick.com',
            'password' => bcrypt('cleanclick123'),
        ]);

        return redirect()->back()->with('success', 'Pelanggan baru bernama "' . $pelanggan->name . '" berhasil ditambahkan!');
    }
}