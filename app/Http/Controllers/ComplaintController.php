<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    // === SISI USER ===
    public function indexUser()
    {
        $chats = Complaint::where('user_id', Auth::id())->orderBy('created_at', 'asc')->get();
        return view('user.komplain', compact('chats'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'pesan' => 'required_without:bukti_foto',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $namaFoto = null;
        if ($request->hasFile('bukti_foto')) {
            // Laravel akan mengacak nama file secara aman dan menyimpannya ke folder tujuan
            $path = $request->file('bukti_foto')->store('public/bukti_komplain');
            $namaFoto = basename($path); // Mengambil nama acak filenya saja untuk disimpan di DB
        }

        Complaint::create([
            'user_id' => Auth::id(),
            'pesan' => $request->pesan ?? 'Mengirimkan foto bukti.',
            'bukti_foto' => $namaFoto,
            'pengirim' => 'user',
            'status' => 'Diproses'
        ]);

        return redirect()->back()->with('success', 'Pesan komplain berhasil dikirim!');
    }

    // === SISI ADMIN ===
    public function indexAdmin()
    {
        $listKomplain = Complaint::with('user')
            ->select('user_id', \DB::raw('MAX(created_at) as last_chat'))
            ->groupBy('user_id')
            ->orderBy('last_chat', 'desc')
            ->get();

        return view('admin.list_komplain', compact('listKomplain'));
    }

    public function detailAdmin($userId)
    {
        $chats = Complaint::where('user_id', $userId)->orderBy('created_at', 'asc')->get();
        $user = \App\Models\User::findOrFail($userId);

        return view('admin.detail_komplain', compact('chats', 'user'));
    }

    public function storeAdmin(Request $request, $userId)
    {
        $request->validate([
            'pesan' => 'required',
        ]);

        Complaint::create([
            'user_id' => $userId,
            'pesan' => $request->pesan,
            'pengirim' => 'admin',
            'status' => 'Diproses'
        ]);

        return redirect()->back();
    }
}