<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Pelanggan - CleanClick</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen py-12 px-4">
    <div class="bg-white p-8 rounded-3xl shadow-xl w-full max-w-xl border border-slate-200">
        <a href="https://cleanclickselflaundry.blogspot.com/?fbclid=PAb21jcAS7_gBleHRuA2FlbQIxMQBzcnRjBmFwcF9pZA81NjcwNjczNDMzNTI0MjcAAad9GsDWvYhVnx6Unj2Xrh7IHoKNQrlUgicSK9E32Ef9xjNEWFhivSlclaK6hQ_aem_eHAz8V7PR44ZYfyzBtJFGQ&m=1&utm_source=ig&utm_medium=social&utm_content=link_in_bio" target="_blank"  space-x-2 group transition transform hover:scale-105"><h2 class="text-3xl font-black text-center text-slate-800 mb-2">CleanClick<span class="text-blue-600">.</span></h2></a>
        <p class="text-center text-sm text-slate-500 mb-8">Buat akun pelanggan Anda untuk mulai menikmati layanan premium</p>

        <form action="{{ url('/register') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-200/60 space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">ℹ️ Informasi Profil Akun</h3>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:outline-blue-500" placeholder="Nama Lengkap" required>
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:outline-blue-500" placeholder="nama@gmail.com" required>
                        @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:outline-blue-500" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Rumah Lengkap</label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:outline-blue-500" placeholder="Nama Jalan, Blok, No. Rumah" required>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password Akun</label>
                        <input type="password" name="password" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:outline-blue-500" placeholder="Minimal 6 karakter" required>
                        @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full border border-slate-200 p-3 rounded-xl text-sm focus:outline-blue-500" placeholder="Ketik ulang" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold hover:bg-blue-700 shadow-md transition text-sm cursor-pointer">
                Konfirmasi & Buat Akun Baru 🚀
            </button>
            
            <p class="text-center text-xs text-slate-500 mt-4">
                Sudah memiliki akun? <a href="{{ url('/login') }}" class="text-blue-600 font-bold hover:underline">Masuk di Sini</a>
            </p>
        </form>
    </div>
</body>
</html>