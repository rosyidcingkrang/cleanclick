<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat dengan {{ $user->name }} - Admin CleanClick</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

    <main class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden flex flex-col h-[650px]">
            
            <!-- Header Obrolan Admin -->
            <div class="bg-slate-900 p-4 text-white flex justify-between items-center border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-800 text-blue-400 font-bold flex items-center justify-center text-sm border border-slate-700">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Keluhan: {{ $user->name }}</h3>
                        <p class="text-[11px] text-emerald-400 font-medium flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Terhubung sebagai Admin
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.komplain.index') }}" class="text-xs bg-slate-800 hover:bg-slate-700 px-3 py-1.5 rounded-xl font-semibold border border-slate-700 transition">
                    ⬅️ Kembali ke List
                </a>
            </div>

            <!-- Area Isi Obrolan / Chat Box -->
            <div class="flex-1 p-6 overflow-y-auto space-y-4 bg-slate-50/60" id="adminChatContainer">
                @foreach($chats as $chat)
                    @if($chat->pengirim == 'admin')
                        <!-- Balon Chat Admin (Kanan) -->
                        <div class="flex flex-col items-end space-y-1">
                            <div class="bg-blue-600 text-white rounded-2xl rounded-tr-none px-4 py-2.5 max-w-md shadow-sm text-sm">
                                <p>{{ $chat->pesan }}</p>
                            </div>
                            <span class="text-[10px] text-slate-400 mr-1">{{ $chat->created_at->format('H:i') }}</span>
                        </div>
                    @else
                        <!-- Balon Chat User / Pelanggan (Kiri) -->
                        <div class="flex flex-col items-start space-y-1">
                            <span class="text-[10px] font-bold text-slate-500 ml-1">👤 {{ $user->name }} (Pelanggan)</span>
                            <div class="bg-white text-slate-800 border border-slate-200 rounded-2xl rounded-tl-none px-4 py-2.5 max-w-md shadow-sm text-sm">
                                <p>{{ $chat->pesan }}</p>
                                
                                <!-- Menampilkan Foto Bukti jika dikirim oleh user -->
                                @if($chat->bukti_foto)
                                    <div class="mt-2 group relative">
                                        <a href="{{ asset('storage/bukti_komplain/' . $chat->bukti_foto) }}" target="_blank" title="Klik untuk memperbesar">
                                            <img src="{{ asset('storage/bukti_komplain/' . $chat->bukti_foto) }}" class="rounded-xl max-w-xs border border-slate-200 shadow hover:opacity-95 transition" alt="Bukti Komplain User">
                                        </a>
                                        <span class="text-[10px] text-slate-400 block mt-1 italic">*Klik gambar untuk memperbesar</span>
                                    </div>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400 ml-1">{{ $chat->created_at->format('H:i') }}</span>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Form Pengiriman Balasan Admin -->
            <div class="p-4 bg-white border-t border-slate-200">
                <form action="{{ route('admin.komplain.store', $user->id) }}" method="POST" class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-2xl p-2 focus-within:border-blue-500 transition">
                    @csrf
                    <!-- Input Teks Balasan -->
                    <input type="text" name="pesan" placeholder="Ketik pesan solusi atau balasan kepada pelanggan..." class="w-full bg-transparent text-sm p-2 outline-none text-slate-800" required autocomplete="off">
                    
                    <!-- Tombol Kirim Balasan -->
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2 rounded-xl text-sm transition shrink-0">
                        Kirim Balasan ↩️
                    </button>
                </form>
            </div>

        </div>
    </main>

    <script>
        // Memastikan scroll box otomatis langsung mengarah ke chat paling bawah/baru saat dimuat
        const chatWindow = document.getElementById("adminChatContainer");
        chatWindow.scrollTop = chatWindow.scrollHeight;
    </script>
</body>
</html>