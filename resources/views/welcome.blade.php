<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CleanClick — Premium Laundry</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 flex flex-col justify-between min-h-screen font-sans">
    
    <!-- HEADER / NAVBAR -->
    <header class="bg-white shadow-sm py-4 px-8 flex justify-between items-center">
        <span class="text-2xl font-black text-blue-600">CleanClick.</span>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="text-slate-600 font-medium px-4 py-2 hover:text-blue-600 transition">Masuk</a>
            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-5 py-2 rounded-xl font-medium hover:bg-blue-700 transition">Daftar Sekarang</a>
        </div>
    </header>

    <!-- MAIN HERO CONTENT -->
    <main class="max-w-4xl mx-auto text-center px-6 py-12 flex flex-col items-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mb-4">
            Pakaian Bersih Sempurna,<br>Tanpa Ribet Antre
        </h1>
        <p class="text-base md:text-lg text-slate-600 max-w-2xl mb-8">
            Platform laundry digital cerdas. Daftarkan cucian Anda secara mandiri, pilih layanan tetap, bayar dengan fleksibel, dan pantau posisi pakaian Anda secara real-time.
        </p>

        <a href="{{ route('login') }}" class="bg-slate-900 text-white px-8 py-3.5 rounded-xl font-bold text-base hover:bg-slate-800 shadow-lg transition mb-12">
            Mulai Laundry Sekarang
        </a>

        <!-- CARD TRACKING STATUS CUCIAN -->
        <div class="w-full max-w-xl bg-white border border-slate-100 p-6 md:p-8 rounded-2xl shadow-xl">
            <div class="flex items-center justify-center gap-2 mb-2">
                <span class="text-xl">🔍</span>
                <h3 class="text-lg font-bold text-slate-800">Tracking Cepat Status Cucian</h3>
            </div>
            <p class="text-xs text-slate-500 mb-6">Pantau progres pengerjaan kain Anda langsung tanpa perlu masuk ke akun</p>

            <!-- FORM TRACKING -->
            <form action="{{ route('cek.progres') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-left">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" required placeholder="Contoh: Intan" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Nota / ID Laundry</label>
                        <input type="text" name="nomor_nota" required placeholder="Contoh: INV-20260727-007" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-blue-500 transition">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-blue-700 shadow-md transition mt-2">
                    Cek Status Progres Sekarang
                </button>
            </form>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="text-center py-6 text-sm text-slate-400 border-t border-slate-100 bg-white">
        &copy; 2026 CleanClick Laundry. All rights reserved.
    </footer>

</body>
</html>