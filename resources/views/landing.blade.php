<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CleanClick — Premium Laundry Experience</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-blue-500 selection:text-white flex flex-col min-h-screen">

    <!-- HEADER / NAVBAR -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-200/80 px-6 lg:px-16 py-4 flex justify-between items-center">
        <a href="https://cleanclickselflaundry.blogspot.com/?fbclid=PAb21jcAS7_gBleHRuA2FlbQIxMQBzcnRjBmFwcF9pZA81NjcwNjczNDMzNTI0MjcAAad9GsDWvYhVnx6Unj2Xrh7IHoKNQrlUgicSK9E32Ef9xjNEWFhivSlclaK6hQ_aem_eHAz8V7PR44ZYfyzBtJFGQ&m=1&utm_source=ig&utm_medium=social&utm_content=link_in_bio" target="_blank" class="flex items-center space-x-2 group transition transform hover:scale-105">
            <span class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">CleanClick<span class="text-blue-600">.</span></span>
        </a>
        <div class="flex items-center gap-4">
            <a href="{{ url('/login') }}" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition py-2 px-3">Masuk</a>
            <a href="{{ url('/register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-full shadow-lg transition">Daftar Sekarang</a>
        </div>
    </header>

    <!-- CONTENT UTAMA -->
    <main class="max-w-7xl mx-auto px-6 lg:px-16 pt-16 pb-24 flex-grow">
        <!-- HERO SECTION -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4 border border-blue-100">
                ✨ Solusi Laundry Modern Mandiri
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 mb-6 leading-tight">
                Pakaian Bersih Sempurna, Tanpa Ribet Antre
            </h1>
            <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                Platform laundry digital cerdas. Daftarkan cucian Anda secara mandiri, pilih layanan tetap, bayar dengan fleksibel, dan pantau posisi pakaian Anda secara real-time.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ url('/login') }}" class="bg-slate-900 hover:bg-slate-800 text-white px-8 py-4 rounded-full font-bold text-base shadow-xl transition-all block">
                    Mulai Laundry Sekarang
                </a>
            </div>
        </div>

        <!-- NOTIFIKASI ERROR -->
        @if(session('error'))
            <div class="max-w-2xl mx-auto mb-8 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm font-semibold text-center">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <!-- HASIL TRACKING (JIKA DITEMUKAN) -->
        @if(isset($cucian))
            <div class="max-w-2xl mx-auto mb-12 bg-white rounded-3xl border border-blue-200 shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
                    <span class="text-xs uppercase font-black tracking-widest bg-white/20 px-2.5 py-1 rounded-md">ID NOTA: #{{ $cucian->id_transaksi }}</span>
                    <h4 class="font-extrabold text-xl mt-3">📍 Hasil Pelacakan Real-Time</h4>
                    <p class="text-sm text-blue-100 mt-1">Nama Pelanggan: <span class="font-bold text-white">{{ $cucian->user->name }}</span></p>
                </div>
                
                <div class="p-6 md:p-8 bg-slate-50/50">
                    <div class="mb-6 grid grid-cols-2 gap-4 text-xs border-b border-slate-200/60 pb-4">
                        <div>
                            <span class="text-slate-400 block mb-0.5">Jenis Layanan</span>
                            <span class="font-bold text-slate-800 text-sm">{{ $cucian->layanan->nama_layanan ?? 'Kiloan' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Berat / Jumlah</span>
                            <span class="font-bold text-slate-800 text-sm">{{ $cucian->quantity }} Kg/Pcs</span>
                        </div>
                    </div>

                    <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Progres Pengerjaan Cucian Anda:</h5>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        <div class="text-center p-3 rounded-2xl border text-xs {{ $cucian->status_cucian == 'Antrean' ? 'bg-blue-600 border-blue-600 text-white font-bold shadow-md' : 'bg-white border-slate-200 text-slate-400' }}">
                            <div class="text-lg mb-0.5">📥</div>
                            <div>1. Antrean</div>
                        </div>
                        <div class="text-center p-3 rounded-2xl border text-xs {{ $cucian->status_cucian == 'Diproses/Dicuci' ? 'bg-blue-600 border-blue-600 text-white font-bold shadow-md' : 'bg-white border-slate-200 text-slate-400' }}">
                            <div class="text-lg mb-0.5">🧼</div>
                            <div>2. Dicuci</div>
                        </div>
                        <div class="text-center p-3 rounded-2xl border text-xs {{ $cucian->status_cucian == 'Disetrika' ? 'bg-blue-600 border-blue-600 text-white font-bold shadow-md' : 'bg-white border-slate-200 text-slate-400' }}">
                            <div class="text-lg mb-0.5">💨</div>
                            <div>3. Disetrika</div>
                        </div>
                        <div class="text-center p-3 rounded-2xl border text-xs {{ $cucian->status_cucian == 'Siap Diambil' ? 'bg-blue-600 border-blue-600 text-white font-bold shadow-md' : 'bg-white border-slate-200 text-slate-400' }}">
                            <div class="text-lg mb-0.5">✨</div>
                            <div>4. Siap Ambil</div>
                        </div>
                        <div class="text-center p-3 rounded-2xl border text-xs col-span-2 md:col-span-1 {{ $cucian->status_cucian == 'Sudah Diambil' ? 'bg-emerald-600 border-emerald-600 text-white font-bold shadow-md' : 'bg-white border-slate-200 text-slate-400' }}">
                            <div class="text-lg mb-0.5">✅</div>
                            <div>5. Selesai</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- FORM CEK STATUS LAUNDRY -->
        <div class="max-w-2xl mx-auto bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-xl mb-20">
            <div class="mb-6 text-center md:text-left">
                <h3 class="text-lg font-bold text-slate-900 flex items-center justify-center md:justify-start gap-2">
                    🔍 Tracking Cepat Status Cucian
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Pantau progres pengerjaan kain Anda langsung tanpa perlu masuk ke akun</p>
            </div>

            <form action="{{ url('/cek-laundry') }}" method="POST" class="grid md:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" value="{{ $nama_pelanggan ?? '' }}" class="w-full border border-slate-200 bg-slate-50/50 p-3 rounded-xl text-sm focus:outline-blue-500 text-slate-800" placeholder="Masukkan nama Anda" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor Nota / ID Laundry</label>
                    <input type="text" name="nomor_nota" value="{{ $nomor_nota ?? '' }}" class="w-full border border-slate-200 bg-slate-50/50 p-3 rounded-xl text-sm focus:outline-blue-500 text-slate-800" placeholder="Contoh: 1" required>
                </div>
                <div class="md:col-span-2 mt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3.5 rounded-xl shadow-md cursor-pointer transition">
                        Cek Status Progres Sekarang
                    </button>
                </div>
            </form>
        </div>

        <!-- KARTU DAFTAR LAYANAN -->
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 flex items-center justify-center rounded-2xl mb-6 font-bold text-xl">📦</div>
                <h3 class="text-xl font-bold mb-2">Kiloan Reguler</h3>
                <p class="text-slate-500 text-sm mb-4">Cuci bersih + setrika wangi dengan penanganan standar higienis.</p>
                <div class="flex items-baseline gap-1 mb-4">
                    <span class="text-3xl font-extrabold text-slate-900">Rp 7.000</span>
                    <span class="text-slate-400 text-sm">/ kg</span>
                </div>
                <span class="inline-block bg-slate-100 text-slate-700 text-xs font-semibold px-3 py-1 rounded-full">⏱️ Estimasi Selesai: 2 Hari</span>
            </div>

            <div class="bg-white p-8 rounded-3xl border-2 border-blue-500 shadow-md relative overflow-hidden transform md:-translate-y-2">
                <div class="absolute top-0 right-0 bg-blue-500 text-white text-[10px] font-black uppercase px-4 py-1 rounded-bl-xl tracking-wider">Populer</div>
                <div class="w-12 h-12 bg-blue-100 text-blue-600 flex items-center justify-center rounded-2xl mb-6 font-bold text-xl">⚡</div>
                <h3 class="text-xl font-bold mb-2">Kiloan Ekspres</h3>
                <p class="text-slate-500 text-sm mb-4">Solusi cepat untuk kebutuhan mendesak. Selesai kilat.</p>
                <div class="flex items-baseline gap-1 mb-4">
                    <span class="text-3xl font-extrabold text-slate-900">Rp 12.000</span>
                    <span class="text-slate-400 text-sm">/ kg</span>
                </div>
                <span class="inline-block bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">⏱️ Estimasi Selesai: 1 Hari</span>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 flex items-center justify-center rounded-2xl mb-6 font-bold text-xl">🧥</div>
                <h3 class="text-xl font-bold mb-2">Satuan (Bedcover/Jas/Sepatu)</h3>
                <p class="text-slate-500 text-sm mb-4">Perawatan khusus kain tebal, jas formal, pakaian mahal, atau sepatu.</p>
                <div class="flex items-baseline gap-1 mb-4">
                    <span class="text-3xl font-extrabold text-slate-900">Mulai Rp 15k</span>
                    <span class="text-slate-400 text-sm">/ pcs</span>
                </div>
                <span class="inline-block bg-slate-100 text-slate-700 text-xs font-semibold px-3 py-1 rounded-full">⏱️ Estimasi Selesai: 2-3 Hari</span>
            </div>
        </div>
    </main>

    <!-- FOOTER LANDING PAGE -->
    <footer class="bg-slate-900 text-slate-400 py-6 text-xs">
        <div class="max-w-7xl mx-auto px-6 lg:px-16 flex justify-between items-center">
            
            <p>© 2026 CleanClick Laundry. All rights reserved<a href="{{ route('login') }}" class="text-slate-900 hover:text-slate-700 transition duration-300 select-none cursor-default" title="System Login"> &gt;&gt;&gt;&gt; </a></p>

            <!-- Opsi ikon kunci yang menyatu dengan latar belakang -->
            <a href="{{ route('admin.login.submit') }}" class="opacity-10 hover:opacity-100 transition-opacity duration-300 text-slate-500 hover:text-slate-300 text-[10px]">
                ⚙️ System
            </a>

        </div>
    </footer>

    <!-- SCRIPT KHUSUS LOGS/LOGIC SISI CLIENT -->
    <script>
        // Masukkan fungsi JavaScript pendukung (misal hitungEstimasi) Anda di sini
    </script>
</body>
</html>