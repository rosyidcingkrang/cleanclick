<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - CleanClick</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 antialiased">
    
    <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 max-w-md w-full text-center">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">CleanClick<span class="text-blue-600">.</span></h1>
        <p class="text-slate-400 text-xs mt-2 font-medium">Silakan buat password baru yang aman untuk akun Anda</p>

        @if ($errors->any())
            <div class="mt-4 bg-rose-50 text-rose-700 p-3 rounded-xl text-xs font-semibold border border-rose-100 text-left">
                ⚠️ Terjadi kesalahan. Pastikan password minimal 8 karakter dan konfirmasi kecocokan password benar.
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="mt-6 text-left space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password Baru</label>
                <input type="password" name="password" class="w-full border border-slate-200 bg-slate-50/50 p-3 rounded-xl text-sm focus:outline-blue-500" required placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="w-full border border-slate-200 bg-slate-50/50 p-3 rounded-xl text-sm focus:outline-blue-500" required placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="w-full bg-emerald-500 text-white py-3.5 rounded-xl font-bold text-sm hover:bg-emerald-600 transition shadow-lg shadow-emerald-100 cursor-pointer">
                Simpan Password & Login ✔️
            </button>
        </form>
    </div>

</body>
</html>