<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CleanClick - Login Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full border border-slate-100">
        <div class="text-center mb-6">
            <span class="bg-blue-100 text-blue-700 text-[10px] font-extrabold uppercase tracking-widest px-3 py-1 rounded-full">Gerbang Internal</span>
            <h1 class="text-2xl font-black text-slate-800 mt-2">CleanClick Admin</h1>
            <p class="text-xs text-slate-400 mt-1">Silakan masuk untuk mengelola antrean laundry toko</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 text-rose-600 p-3 rounded-xl mb-4 text-xs font-semibold border border-rose-100">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Email Resmi Admin</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-slate-200 bg-slate-50 p-2.5 rounded-xl text-sm focus:outline-blue-600" placeholder="nama@cleanclick.com" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Kata Sandi</label>
                <input type="password" name="password" class="w-full border border-slate-200 bg-slate-50 p-2.5 rounded-xl text-sm focus:outline-blue-600" placeholder="••••••••" required>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold text-sm hover:bg-slate-800 transition cursor-pointer">
                Masuk ke Sistem Admin
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-slate-100">
            <p class="text-xs text-slate-400">Petugas Baru? <a href="{{ route('admin.register') }}" class="text-blue-600 font-bold hover:underline">Daftar Akun Admin</a></p>
        </div>
    </div>

</body>
</html>