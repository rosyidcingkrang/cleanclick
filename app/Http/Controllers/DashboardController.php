<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Http\Request;

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
            'nomor_nota'     => 'required|string|max:50', // Diubah ke string karena format nota: INV-YYYYMMDD-01
        ]);

        $nama = trim($request->input('nama_pelanggan'));
        $nota = trim($request->input('nomor_nota'));

        // Mencari berdasarkan no_nota atau id_transaksi (mencakup pencarian jika user hanya memasukkan angka ID)
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
            'nomor_nota'     => $nota
        ]);
    }

    public function adminDashboard()
    {
        // Menghitung total pendapatan hari ini dari transaksi lunas
        $totalPendapatanHariIni = Transaksi::whereDate('tanggal', now()->today())
            ->where('status_pembayaran', 'Lunas')
            ->sum('total_harga');

        $transaksi = Transaksi::with(['user', 'layanan'])
            ->orderBy('id_transaksi', 'desc')
            ->get();
            
        $layanan = Layanan::all();
        
        // Mengambil semua user dengan role 'user' atau 'pelanggan'
        $pelanggan = User::whereIn('role', ['user', 'pelanggan'])
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.dashboard', compact('totalPendapatanHariIni', 'transaksi', 'layanan', 'pelanggan'));
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
            'id_layanan'        => 'required|exists:layanans,id', // Diperketat dengan cek ke DB
            'quantity'          => 'required|numeric|min:0.1',
            'status_pembayaran' => 'required|string',
            'metode_pembayaran' => 'required|string',
        ]);

        $layanan = Layanan::findOrFail($request->id_layanan);
        $totalHarga = $layanan->harga_satuan * $request->quantity;

        $transaksi = new Transaksi();
        
        // JIKA DIINPUT ADMIN: Gunakan user_id dari form.
        // JIKA DIINPUT USER: Gunakan auth()->id().
        $transaksi->user_id = $request->filled('user_id') ? $request->user_id : auth()->id(); 
        
        $transaksi->id_layanan       = $request->id_layanan;
        $transaksi->quantity         = $request->quantity;
        $transaksi->total_harga      = $totalHarga; 
        $transaksi->status_pembayaran = $request->status_pembayaran;
        $transaksi->metode_pembayaran = $request->metode_pembayaran;
        $transaksi->status_cucian     = 'Antrean';
        
        // Otomatisasi Nomor Nota Aman (Mencegah Duplikasi/Crash jika ada order yang pernah dihapus)
        $hariIni = now()->format('Ymd');
        $transaksiTerakhirHariIni = Transaksi::whereDate('created_at', now()->today())
            ->orderBy('id_transaksi', 'desc')
            ->first();

        $urutan = 1;
        if ($transaksiTerakhirHariIni && $transaksiTerakhirHariIni->no_nota) {
            $parts = explode('-', $transaksiTerakhirHariIni->no_nota);
            $urutanTerakhir = (int) end($parts);
            $urutan = $urutanTerakhir + 1;
        }

        $transaksi->no_nota = 'INV-' . $hariIni . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
        $transaksi->tanggal = now()->format('Y-m-d');
        $transaksi->save();

        return redirect()->back()->with('success', 'Transaksi Laundry Baru Berhasil Disimpan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Antrean,Diproses/Dicuci,Disetrika,Selesai & Siap Diambil,Sudah Diambil'
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

        $pelanggan = new User();
        $pelanggan->name     = $request->name;
        $pelanggan->whatsapp = $request->whatsapp;
        $pelanggan->role     = 'user'; 
        
        // Membuat Email unik berbasis timestamp + random string kecil
        $pelanggan->email    = 'offline_' . time() . rand(10, 99) . '@cleanclick.com';
        $pelanggan->password = bcrypt('cleanclick123'); 
        
        $pelanggan->save();

        return redirect()->back()->with('success', 'Pelanggan baru bernama "' . $pelanggan->name . '" berhasil ditambahkan!');
    }
}