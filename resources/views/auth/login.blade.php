<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CleanClick</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-slate-100">
        <a href="https://cleanclickselflaundry.blogspot.com/?fbclid=PAb21jcAS7_gBleHRuA2FlbQIxMQBzcnRjBmFwcF9pZA81NjcwNjczNDMzNTI0MjcAAad9GsDWvYhVnx6Unj2Xrh7IHoKNQrlUgicSK9E32Ef9xjNEWFhivSlclaK6hQ_aem_eHAz8V7PR44ZYfyzBtJFGQ&m=1&utm_source=ig&utm_medium=social&utm_content=link_in_bio" target="_blank" space-x-2 group transition transform hover:scale-105"><h2 class="text-3xl font-black text-center text-slate-800 mb-2">CleanClick<span class="text-blue-600">.</span></h2></a>
        <p class="text-center text-sm text-slate-500 mb-8">Silakan masuk menggunakan akun anda</p>

        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm font-medium">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
    @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-slate-200 p-3 rounded-xl focus:outline-blue-500 text-sm" placeholder="budi123@gmail.com" required>
                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="passwordInput" name="password" class="w-full border border-slate-200 p-3 pr-12 rounded-xl focus:outline-blue-500 text-sm" placeholder="••••••••" required>
                    <!-- Letakkan kode ini persis di bawah kolom input Password Anda -->
<div class="text-right mt-1 mb-4">
    <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:underline">
        Lupa Password?
    </a>
</div>
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                        <svg id="eyeIconOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        
                        <svg id="eyeIconClose" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-md transition text-sm cursor-pointer">Sign In</button>
            
            <p class="text-center text-xs text-slate-500 mt-6">
                Belum mempunyai akun? <a href="/register" class="text-blue-600 font-bold hover:underline">Daftar disini</a>
            </p>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIconOpen = document.getElementById('eyeIconOpen');
            const eyeIconClose = document.getElementById('eyeIconClose');

            if (passwordInput.type === 'password') {
                // Ubah ke teks agar terlihat
                passwordInput.type = 'text';
                // Tukar tampilan ikon mata
                eyeIconOpen.classList.add('hidden');
                eyeIconClose.classList.remove('hidden');
            } else {
                // Kembalikan ke password agar tersembunyi
                passwordInput.type = 'password';
                // Tukar kembali tampilan ikon mata
                eyeIconOpen.classList.remove('hidden');
                eyeIconClose.classList.add('hidden');
            }
        }
    </script>
</body>
</html>