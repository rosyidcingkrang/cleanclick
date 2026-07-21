<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Komplain Pelanggan - Admin CleanClick</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

    <header class="bg-white border-b border-slate-200 px-6 lg:px-16 py-4 flex justify-between items-center">
        <span class="text-xl font-extrabold text-slate-800">Panel Admin <span class="text-blue-600">CleanClick.</span></span>
        <a href="/admin/dashboard" class="text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl transition">Kembali ke Dashboard</a>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-6">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">📥 Masuk Komplain Pelanggan</h2>
            <p class="text-sm text-slate-500 mt-0.5">Berikut daftar user yang membutuhkan bantuan atau mengirimkan keluhan kelayakan layanan.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden divide-y divide-slate-100">
            @forelse($listKomplain as $komplain)
                <a href="{{ route('admin.komplain.detail', $komplain->user_id) }}" class="p-5 flex items-center justify-between hover:bg-slate-50 transition block">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-lg shadow-sm border border-blue-100">
                            {{ strtoupper(substr($komplain->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-base">{{ $komplain->user->name }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Email: {{ $komplain->user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end gap-1.5">
                        <span class="text-[11px] font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
                            Aktif: {{ \Carbon\Carbon::parse($komplain->last_chat)->diffForHumans() }}
                        </span>
                        <span class="text-xs bg-blue-600 text-white font-bold px-3 py-1.5 rounded-xl hover:bg-blue-700 flex items-center gap-1">
                            Buka Chat 💬
                        </span>
                    </div>
                </a>
            @empty
                <div class="text-center py-20 text-slate-400 text-sm">
                    📭 Saat ini tidak ada komplain atau keluhan aktif dari pelanggan.
                </div>
            @endforelse
        </div>
    </main>

</body>
</html>