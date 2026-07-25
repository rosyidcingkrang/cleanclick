<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - CleanClick</title>
    <!-- SKRIP UTAMA TAILWIND CSS V4 UNTUK MEMUNCULKAN DESAIN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

    <!-- Navbar Atas Dashboard User -->
    <header class="bg-white border-b border-slate-200/80 px-6 lg:px-16 py-4 flex justify-between items-center">
        <a href="https://cleanclickselflaundry.blogspot.com/" target="_blank" class="flex items-center space-x-2 group transition transform hover:scale-105">
            <span class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">CleanClick<span class="text-blue-600">.</span></span>
        </a>
        <div class="flex items-center gap-4">
            <span class="text-sm font-semibold text-slate-600">Halo, {{ auth()->user()->name }} 👋</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-xs bg-red-50 text-red-600 font-bold px-3 py-1.5 rounded-lg hover:bg-red-100 transition cursor-pointer">Keluar</button>
            </form>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 lg:px-16 pt-12 pb-24">
        
        <!-- FORM ORDER / DETAIL BERAT -->
        <div class="max-w-2xl mx-auto bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-xl mb-12">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    🧺 Detail Berat & Layanan Cucian
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Silakan masukkan rincian pakaian Anda untuk mengajukan antrean laundry baru</p>
            </div>

            <form action="{{ route('user.transaksi.store') }}" method="POST" class="space-y-5">
                @csrf
                
                @if(session('success'))
                    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl text-sm font-semibold mb-4 border border-emerald-100">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <div class="grid md:grid-cols-2 gap-4">
                    <!-- Bagian Pilihan Layanan (Dropdown) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Pilih Jenis Layanan <span class="text-red-500">*</span>
                        </label>
                        <select name="id_layanan" id="layananSelect" onchange="updateLayananLengkap()" class="w-full border border-slate-200 bg-slate-50/50 p-3 rounded-xl text-sm focus:outline-blue-500 text-slate-800" required>
                            <option value="" disabled selected>-- Pilih Layanan --</option>
                            @foreach($layananPilihan as $l)
                                @php
                                    $namaLower = strtolower($l->nama_layanan);
                                    // Tentukan jenis satuan berdasarkan nama layanan
                                    $isPcs = Str::contains($namaLower, ['bedcover', 'sepatu', 'jas', 'satuan']);
                                    $satuan = $isPcs ? 'Pcs' : 'Kg';
                                @endphp
                                <option value="{{ $l->id_layanan }}" data-waktu="{{ $l->estimasi_hari }}" data-satuan="{{ $satuan }}" data-nama="{{ $l->nama_layanan }}">
                                    {{ $l->nama_layanan }} — Rp {{ number_format($l->harga_satuan) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Input Jumlah / Berat dengan badge dinamis -->
<div>
    <label id="labelQuantity" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
        JUMLAH (PCS) <span class="text-red-500">*</span>
    </label>
    <div class="relative flex items-center">
        <!-- step="1" dan min="1" memastikan input wajib bilangan bulat -->
        <input type="number" name="quantity" id="quantityInput" step="1" min="1" class="w-full border border-slate-200 bg-slate-50/50 p-3 pr-16 rounded-xl text-sm focus:outline-blue-500 text-slate-800" placeholder="1" required>
        <span id="unitBadge" class="absolute right-3 text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md pointer-events-none transition-all">
            Pcs
        </span>
    </div>
    <span class="text-[10px] text-slate-400 mt-1 block">*Masukkan jumlah dalam satuan Pcs (minimal 1)</span>
</div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <!-- Status Pembayaran -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Status Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="status_pembayaran" id="statusPembayaran" onchange="updateMetodePembayaran()" class="w-full border border-slate-200 bg-slate-50/50 p-3 rounded-xl text-sm focus:outline-blue-500 text-slate-800" required>
                            <option value="" disabled selected>-- Pilih Status --</option>
                            <option value="Belum Lunas">Tunai (Bayar di Toko)</option>
                            <option value="Lunas">Non-Tunai</option>
                        </select>
                    </div>
                    
                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="metode_pembayaran" id="metodePembayaran" class="w-full border border-slate-200 bg-slate-50/50 p-3 rounded-xl text-sm focus:outline-blue-500 text-slate-800" required>
                            <option value="" disabled selected>-- Pilih Metode --</option>
                        </select>
                    </div>
                </div>

                <!-- Bagian Estimasi Waktu Real-Time -->
                <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-2xl flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-medium">⏱️ Estimasi Waktu Kerja:</span>
                    <span id="estimasiTeks" class="font-bold text-blue-700">Silakan pilih jenis layanan</span>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3.5 rounded-xl shadow-md transition transform hover:-translate-y-0.5 cursor-pointer">
                    Konfirmasi & Ajukan Cucian 🚀
                </button>
            </form>
        </div>

        <!-- PROGRES PENGERJAAN CUCIAN -->
        @if($transaksi->count() > 0)
            @php 
                $activeOrder = $transaksi->first(); 
                $currentStatus = $activeOrder->status_cucian ?? $activeOrder->status;
            @endphp
            
            <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm mb-12">
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Progres Pengerjaan Cucian Anda:</h4>
                        <p class="text-sm font-bold text-slate-700 mt-1">Nota Aktif: <span class="text-blue-600">#{{ $activeOrder->id_transaksi }}</span></p>
                    </div>
                    <span class="text-xs bg-slate-100 text-slate-600 px-3 py-1 rounded-lg font-medium">Update Terakhir: {{ $activeOrder->updated_at->diffForHumans() ?? $activeOrder->tanggal }}</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center">
                    <div class="p-4 rounded-2xl border transition duration-300 flex flex-col items-center justify-center gap-2 {{ $currentStatus == 'Antrean' ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-white border-slate-200 text-slate-400' }}">
                        <span class="text-2xl">📥</span>
                        <span class="text-xs font-bold tracking-tight">1. Antrean</span>
                    </div>
                    <div class="p-4 rounded-2xl border transition duration-300 flex flex-col items-center justify-center gap-2 {{ $currentStatus == 'Dicuci' ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-white border-slate-200 text-slate-400' }}">
                        <span class="text-2xl">🧼</span>
                        <span class="text-xs font-bold tracking-tight">2. Dicuci</span>
                    </div>
                    <div class="p-4 rounded-2xl border transition duration-300 flex flex-col items-center justify-center gap-2 {{ $currentStatus == 'Disetrika' ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-white border-slate-200 text-slate-400' }}">
                        <span class="text-2xl">💨</span>
                        <span class="text-xs font-bold tracking-tight">3. Disetrika</span>
                    </div>
                    <div class="p-4 rounded-2xl border transition duration-300 flex flex-col items-center justify-center gap-2 {{ $currentStatus == 'Siap Ambil' ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-white border-slate-200 text-slate-400' }}">
                        <span class="text-2xl">✨</span>
                        <span class="text-xs font-bold tracking-tight">4. Siap Ambil</span>
                    </div>
                    <div class="p-4 rounded-2xl border transition duration-300 flex flex-col items-center justify-center gap-2 col-span-2 sm:col-span-1 {{ $currentStatus == 'Selesai' ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-white border-slate-200 text-slate-400' }}">
                        <span class="text-2xl">✅</span>
                        <span class="text-xs font-bold tracking-tight">5. Selesai</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- RIWAYAT TRANSAKSI USER -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-4">📋 Riwayat Cucian Anda</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-xs uppercase font-bold">
                            <th class="py-3 px-4">ID Nota</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Layanan</th>
                            <th class="py-3 px-4">Berat / Jumlah</th>
                            <th class="py-3 px-4">Status Cucian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($transaksi as $t)
                        <tr>
                            <td class="py-4 px-4 font-bold text-blue-600">#{{ $t->id_transaksi }}</td>
                            <td class="py-4 px-4">{{ $t->tanggal }}</td>
                            <td class="py-4 px-4">{{ $t->layanan->nama_layanan ?? 'Kiloan' }}</td>
                            <td class="py-4 px-4">{{ $t->quantity }}</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $t->status_cucian ?? $t->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-400">Belum ada riwayat pengajuan laundry.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- JavaScript Interaktif -->
    <script>
    function updateLayananLengkap() {
        const select = document.getElementById('layananSelect');
        const opsiTerpilih = select.options[select.selectedIndex];
        const teksEstimasi = document.getElementById('estimasiTeks');
        const unitBadge = document.getElementById('unitBadge');
        const labelQuantity = document.getElementById('labelQuantity');
        const quantityInput = document.getElementById('quantityInput');
        
        if (select.value) {
            // 1. Update Estimasi Waktu
            const hari = opsiTerpilih.getAttribute('data-waktu');
            teksEstimasi.innerText = hari + " Hari Kerja (Selesai Otomatis)";

            // 2. Update Satuan (Pcs vs Kg)
            const satuan = opsiTerpilih.getAttribute('data-satuan');
            unitBadge.innerText = satuan;
            
            if (satuan === 'Pcs') {
                unitBadge.className = "absolute right-3 text-xs font-bold text-blue-700 bg-blue-100 px-2.5 py-1 rounded-md pointer-events-none transition-all";
                labelQuantity.innerHTML = 'Jumlah (Pcs) <span class="text-red-500">*</span>';
                quantityInput.step = "1"; // Untuk Pcs disarankan angka bulat
            } else {
                unitBadge.className = "absolute right-3 text-xs font-bold text-slate-500 bg-slate-200/80 px-2.5 py-1 rounded-md pointer-events-none transition-all";
                labelQuantity.innerHTML = 'Berat (Kg) <span class="text-red-500">*</span>';
                quantityInput.step = "0.1"; // Untuk Kg bisa desimal
            }
        } else {
            teksEstimasi.innerText = "Silakan pilih jenis layanan";
            unitBadge.innerText = "Kg | Pcs";
            unitBadge.className = "absolute right-3 text-xs font-bold text-slate-400 bg-slate-200/60 px-2 py-1 rounded-md pointer-events-none";
            labelQuantity.innerHTML = 'Jumlah / Berat <span class="text-red-500">*</span>';
        }
    }

    function updateMetodePembayaran() {
        const statusSelect = document.getElementById('statusPembayaran');
        const metodeSelect = document.getElementById('metodePembayaran');
        const val = statusSelect.value;

        metodeSelect.innerHTML = '';

        if (val === 'Belum Lunas') {
            const opt = document.createElement('option');
            opt.value = 'Tunai';
            opt.textContent = 'Tunai';
            opt.selected = true;
            metodeSelect.appendChild(opt);
        } else if (val === 'Lunas') {
            const optDefault = document.createElement('option');
            optDefault.value = '';
            optDefault.textContent = '-- Pilih Metode Non-Tunai --';
            optDefault.disabled = true;
            optDefault.selected = true;
            metodeSelect.appendChild(optDefault);

            const optMandiri = document.createElement('option');
            optMandiri.value = 'Transfer Mandiri';
            optMandiri.textContent = 'Transfer Mandiri';
            metodeSelect.appendChild(optMandiri);

            const optQris = document.createElement('option');
            optQris.value = 'QRIS';
            optQris.textContent = 'QRIS';
            metodeSelect.appendChild(optQris);
        }
    }
    </script>

    <!-- TOMBOL CHAT MELAYANG (WHATSAPP) -->
    <div class="fixed bottom-6 right-6 z-50">
        @php
            $nomor_wa = "+6281265604596"; 
            $text = "Halo Admin CleanClick, saya ingin mengajukan komplain.\n\n"
                . "📋 *DETAIL PELANGGAN*:\n"
                . "• Nama: " . Auth::user()->name . "\n"
                . "• ID Pelanggan: USER-" . Auth::id() . "\n"
                . "• ID Nota / Transaksi: " . ($transaksi_terakhir->id_nota ?? 'Belum ada transaksi') . "\n\n"
                . "⚠️ *Rincian Kendala / Keluhan*:\n"
                . "[Tuliskan keluhan Anda di sini...]\n\n"
                . "Mohon bantuannya untuk segera diproses, terima kasih.";

            $url_wa = "https://api.whatsapp.com/send?phone=" . $nomor_wa . "&text=" . urlencode($text);
        @endphp

        <a href="{{ $url_wa }}" target="_blank" class="flex items-center gap-2.5 bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white font-bold px-4 py-3.5 rounded-full shadow-2xl hover:shadow-emerald-200/50 transition transform hover:-translate-y-1 group duration-300">
            <span class="relative flex h-5 w-5 items-center justify-center text-lg group-hover:scale-110 transition">
                💬
                <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-white opacity-40"></span>
            </span>
            <span class="text-xs tracking-wide pr-1">Komplain via WhatsApp</span>
        </a>
    </div>

</body>
</html>