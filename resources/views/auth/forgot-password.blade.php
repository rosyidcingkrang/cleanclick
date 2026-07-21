<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - CleanClick</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 antialiased">
    
    <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 max-w-md w-full text-center">
        <a href="https://cleanclickselflaundry.blogspot.com/?fbclid=PAb21jcAS7_gBleHRuA2FlbQIxMQBzcnRjBmFwcF9pZA81NjcwNjczNDMzNTI0MjcAAad9GsDWvYhVnx6Unj2Xrh7IHoKNQrlUgicSK9E32Ef9xjNEWFhivSlclaK6hQ_aem_eHAz8V7PR44ZYfyzBtJFGQ&m=1&utm_source=ig&utm_medium=social&utm_content=link_in_bio" target="_blank"  space-x-2 group transition transform hover:scale-105"><h1 class="text-3xl font-black text-slate-800 tracking-tight">CleanClick<span class="text-blue-600">.</span></h1></a>
        <p class="text-slate-400 text-xs mt-2 font-medium">Masukkan email Anda untuk menerima tautan pengaturan ulang password</p>

        <!-- Status Notifikasi Sukses -->
        @if (session('status'))
            <div class="mt-4 bg-emerald-50 text-emerald-700 p-3 rounded-xl text-xs font-semibold border border-emerald-100">
                📩 Link reset password telah dikirim ke email Anda!
            </div>
        @endif

        <!-- Validasi Error -->
        @if ($errors->has('email'))
            <div class="mt-4 bg-rose-50 text-rose-700 p-3 rounded-xl text-xs font-semibold border border-rose-100">
                ❌ Email tidak terdaftar dalam sistem.
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="mt-6 text-left space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email Akun</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-slate-200 bg-slate-50/50 p-3 rounded-xl text-sm focus:outline-blue-500 placeholder:text-slate-300" placeholder="contoh: pelanggan@email.com" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-100 cursor-pointer">
                Kirim Link Pengaturan Ulang 🚀
            </button>
        </form>

        <div class="mt-6 text-xs text-slate-400">
            Kembali ke <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Halaman Login</a>
        </div>
    </div>

</body>
</html>