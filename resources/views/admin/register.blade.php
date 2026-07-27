<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CleanClick - Registrasi Admin Baru</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full border border-slate-100">
        <div class="text-center mb-6">
            <span class="bg-indigo-100 text-indigo-700 text-[10px] font-extrabold uppercase tracking-widest px-3 py-1 rounded-full">Pendaftaran Petugas</span>
            <h1 class="text-2xl font-black text-slate-800 mt-2">Registrasi Admin</h1>
            <p class="text-xs text-slate-400 mt-1">Buat akun otoritas kasir/owner CleanClick</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 text-rose-600 p-3 rounded-xl mb-4 text-xs font-semibold border border-rose-100">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.register.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Nama Lengkap Petugas</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-slate-200 bg-slate-50 p-2.5 rounded-xl text-sm focus:outline-indigo-600" placeholder="Nama Kasir" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Email Pekerjaan</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-slate-200 bg-slate-50 p-2.5 rounded-xl text-sm focus:outline-indigo-600" placeholder="admin@cleanclick.com" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Nomor WhatsApp Aktif</label>
                <!-- PERBAIKAN PADA INPUT WHATSAPP DI BAWAH INI -->
                <input type="tel" 
                       inputmode="numeric" 
                       pattern="[0-9]*" 
                       name="whatsapp" 
                       value="{{ old('whatsapp') }}" 
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       class="w-full border border-slate-200 bg-slate-50 p-2.5 rounded-xl text-sm focus:outline-indigo-600" 
                       placeholder="08XXXXXXXXXX" 
                       required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Kata Sandi (Min. 6 Karakter)</label>
                <input type="password" name="password" class="w-full border border-slate-200 bg-slate-50 p-2.5 rounded-xl text-sm focus:outline-indigo-600" placeholder="••••••••" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" class="w-full border border-slate-200 bg-slate-50 p-2.5 rounded-xl text-sm focus:outline-indigo-600" placeholder="••••••••" required>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-indigo-700 transition cursor-pointer shadow-md shadow-indigo-100">
                Daftarkan & Masuk Kerja
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-slate-100">
            <p class="text-xs text-slate-400">Sudah punya akses? <a href="{{ route('admin.login') }}" class="text-indigo-600 font-bold hover:underline">Login Admin</a></p>
        </div>
    </div>

</body>
</html>