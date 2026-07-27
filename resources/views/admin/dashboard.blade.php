<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CleanClick</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen p-6 text-slate-800 antialiased">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Navigation --}}
        <div class="flex justify-between items-center mb-8 bg-white p-4 rounded-2xl shadow-xs border border-slate-100">
            <div>
                <h1 class="text-2xl font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">CleanClick Admin</h1>
                <p class="text-xs text-slate-400">Petugas Aktif: {{ auth()->user()->name }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-rose-500 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-rose-600 transition cursor-pointer">Keluar</button>
            </form>
        </div>

        {{-- Filter Tanggal Laporan --}}
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2 mb-4">
            <input type="date" name="tanggal" value="{{ $selectedDate }}" class="border border-slate-300 p-2 rounded-lg text-sm bg-white focus:outline-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition cursor-pointer">
                Filter Tanggal
            </button>
        </form>

        {{-- Card Total Pendapatan --}}
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-6 rounded-2xl shadow-md mb-8">
            <span class="text-xs uppercase tracking-widest font-bold opacity-75">
                Laporan Keuangan ({{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }})
            </span>
            <h2 class="text-4xl font-black mt-1">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</h2>
        </div>

        {{-- Alert Notifikasi --}}
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6 font-medium text-sm border border-emerald-100 shadow-xs">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 text-rose-700 p-4 rounded-xl mb-6 font-medium text-sm border border-rose-100 shadow-xs">
                ⚠️ Mohon periksa kembali inputan Anda:
                <ul class="list-disc list-inside mt-1 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Form Tambah Order --}}
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-100 h-fit">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Pencatatan Order Baru</h3>
                <form action="{{ route('admin.transaksi.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-bold text-slate-600">Pilih Pelanggan</label>
                            <button type="button" onclick="openModalPelanggan()" class="text-[11px] text-blue-600 font-bold hover:underline cursor-pointer">
                                + Pelanggan Baru
                            </button>
                        </div>
                        <select name="user_id" class="w-full border border-slate-200 bg-slate-50/50 p-2.5 rounded-xl text-sm focus:outline-blue-500 text-slate-700" required>
                            <option value="" disabled selected>-- Pilih Pelanggan --</option>
                            @foreach($pelanggan as $p) 
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->whatsapp ?? '-' }})</option> 
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Pilih Layanan Cucian</label>
                        <select name="id_layanan" class="w-full border border-slate-200 bg-slate-50/50 p-2.5 rounded-xl text-sm focus:outline-blue-500 text-slate-700" required>
                            <option value="" disabled selected>-- Pilih Layanan --</option>
                            @foreach($layanan as $l) 
                                <option value="{{ $l->id_layanan }}">{{ $l->nama_layanan }} - Rp {{ number_format($l->harga_satuan) }}/{{ $l->satuan }}</option> 
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Berat / Jumlah Satuan</label>
                        <input type="number" step="0.01" name="quantity" class="w-full border border-slate-200 bg-slate-50/50 p-2.5 rounded-xl text-sm focus:outline-blue-500" placeholder="Contoh: 3.5 atau 2" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Status Pembayaran</label>
                        <select name="status_pembayaran" class="w-full border border-slate-200 bg-slate-50/50 p-2.5 rounded-xl text-sm focus:outline-blue-500 text-slate-700" required>
                            <option value="Belum Lunas">Belum Lunas</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="w-full border border-slate-200 bg-slate-50/50 p-2.5 rounded-xl text-sm focus:outline-blue-500 text-slate-700" required>
                            <option value="Tunai">Tunai</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-md shadow-blue-100 cursor-pointer">
                        Simpan Order & Cetak Nota
                    </button>
                </form>
            </div>

            {{-- Tabel Monitoring Antrean --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-xs border border-slate-100 h-fit">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Monitoring Antrean Berjalan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                <th class="p-3 font-bold">No Nota</th>
                                <th class="p-3 font-bold">Nama Pelanggan</th>
                                <th class="p-3 font-bold text-center">Kirim Nota</th>
                                <th class="p-3 font-bold">Total & Estimasi</th>
                                <th class="p-3 font-bold text-center">Aksi / Perbarui Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($transaksi as $t)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-3 font-mono text-blue-600 font-bold">{{ $t->no_nota ?? '-' }}</td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-800">{{ $t->user->name ?? 'Pelanggan Tidak Ditemukan' }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $t->user->whatsapp ?? '-' }}</div>
                                </td>
                                
                                <td class="p-3 text-center">
                                    @php
                                        $namaUser = $t->user->name ?? 'Pelanggan';
                                        $pesanWA = urlencode(
                                            "Halo *" . $namaUser . "*, Berikut nota laundry Anda di *CleanClick*:\n\n" .
                                            "📌 *No Nota:* " . $t->no_nota . "\n" .
                                            "🧺 *Layanan:* " . ($t->layanan->nama_layanan ?? '-') . "\n" .
                                            "⚖️ *Qty:* " . $t->quantity . " " . ($t->layanan->satuan ?? 'Kg') . "\n" .
                                            "💰 *Total Biaya:* Rp " . number_format($t->total_harga, 0, ',', '.') . "\n" .
                                            "💳 *Status Bayar:* " . ($t->status_pembayaran ?? 'Belum Lunas') . "\n" .
                                            "⚙️ *Status Cucian:* " . $t->status_cucian . "\n" .
                                            "⏰ *Estimasi:* " . ($t->estimasi_selesai ?? '-') . "\n\n" .
                                            "Terima kasih! 🙏"
                                        );
                                        $nomorHp = $t->user->whatsapp ?? '';
                                        if(str_starts_with($nomorHp, '0')) { $nomorHp = '62' . substr($nomorHp, 1); }
                                    @endphp
                                    <a href="https://api.whatsapp.com/send?phone={{ $nomorHp }}&text={{ $pesanWA }}" target="_blank" class="inline-flex items-center justify-center gap-1 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-1 px-2.5 rounded-lg transition text-[10px] w-24">
                                        💬 WhatsApp
                                    </a>
                                </td>

                                <td class="p-3">
                                    <div class="font-bold text-slate-900">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</div>
                                    <div class="text-[10px] font-medium text-slate-400 mt-0.5">Selesai: {{ $t->estimasi_selesai ?? '-' }}</div>
                                    <span class="inline-block text-[9px] px-2 py-0.5 mt-1 font-bold rounded-full {{ $t->status_pembayaran == 'Lunas' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                        {{ $t->status_pembayaran ?? 'Belum Lunas' }}
                                    </span>
                                </td>
                                
                                <td class="p-3">
                                    <div class="flex items-center gap-2 justify-center">
                                        {{-- 1. Form Simpan Status Cucian --}}
                                        <form action="{{ route('admin.transaksi.update', $t->id_transaksi ?? $t->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex items-center gap-1">
                                                <select name="status" class="border border-slate-200 bg-slate-50/50 p-1.5 rounded-xl text-xs focus:outline-blue-500 text-slate-700">
                                                    <option value="Antrean" {{ $t->status_cucian == 'Antrean' ? 'selected' : '' }}>Antrean</option>
                                                    <option value="Diproses/Dicuci" {{ $t->status_cucian == 'Diproses/Dicuci' ? 'selected' : '' }}>Diproses/Dicuci</option>
                                                    <option value="Disetrika" {{ $t->status_cucian == 'Disetrika' ? 'selected' : '' }}>Disetrika</option>
                                                    <option value="Selesai & Siap Diambil" {{ $t->status_cucian == 'Selesai & Siap Diambil' ? 'selected' : '' }}>Selesai & Siap Diambil</option>
                                                    <option value="Sudah Diambil" {{ $t->status_cucian == 'Sudah Diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                                                </select>
                                                <button type="submit" class="bg-blue-600 text-white font-bold px-3 py-1.5 rounded-xl text-xs hover:bg-blue-700 transition cursor-pointer">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>

                                        {{-- 2. Form Toggle Status Pembayaran (Lunas / Belum Lunas) --}}
                                        <form action="{{ route('admin.transaksi.update', $t->id_transaksi ?? $t->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            @if($t->status_pembayaran == 'Lunas')
                                                <input type="hidden" name="status_pembayaran" value="Belum Lunas">
                                                <button type="submit" title="Ubah ke Belum Lunas" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-2.5 py-1.5 rounded-xl text-xs transition cursor-pointer flex items-center gap-1">
                                                    💵 Ubah
                                                </button>
                                            @else
                                                <input type="hidden" name="status_pembayaran" value="Lunas">
                                                <button type="submit" title="Tandai Lunas" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-2.5 py-1.5 rounded-xl text-xs transition cursor-pointer flex items-center gap-1">
                                                    ✅ Lunas
                                                </button>
                                            @endif
                                        </form>

                                        {{-- 3. Form Hapus Transaksi --}}
                                        <form action="{{ route('admin.transaksi.destroy', $t->id_transaksi ?? $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus orderan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-500 text-white font-bold px-3 py-1.5 rounded-xl text-xs hover:bg-rose-600 transition cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-slate-400">Belum ada transaksi terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    {{-- Modal Pelanggan Baru --}}
    <div id="modalPelanggan" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xl max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-base font-bold text-slate-800">Tambah Pelanggan Baru</h4>
                <button type="button" onclick="closeModalPelanggan()" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">&times;</button>
            </div>
            
            <form action="{{ route('admin.pelanggan.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full border border-slate-200 bg-slate-50/50 p-2.5 rounded-xl text-sm focus:outline-blue-500" placeholder="Masukkan nama pelanggan" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nomor WhatsApp</label>
                    <input type="text" name="whatsapp" class="w-full border border-slate-200 bg-slate-50/50 p-2.5 rounded-xl text-sm focus:outline-blue-500" placeholder="Contoh: 081234567890" required>
                </div>
                
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModalPelanggan()" class="bg-slate-100 text-slate-600 font-bold px-4 py-2 rounded-xl text-xs hover:bg-slate-200 transition cursor-pointer">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white font-bold px-4 py-2 rounded-xl text-xs hover:bg-blue-700 transition cursor-pointer">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModalPelanggan() {
            document.getElementById('modalPelanggan').classList.remove('hidden');
        }
        function closeModalPelanggan() {
            document.getElementById('modalPelanggan').classList.add('hidden');
        }
    </script>
</body>
</html>