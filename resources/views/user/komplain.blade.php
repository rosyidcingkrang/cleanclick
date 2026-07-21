<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Komplain - CleanClick</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

    <main class="max-w-3xl mx-auto px-4 py-8">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden flex flex-col h-[600px]">
            
            <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg">💬 Layanan Pengaduan & Komplain</h3>
                    <p class="text-xs text-blue-100">Hubungi admin jika terjadi kendala pada cucian Anda</p>
                </div>
                <a href="/dashboard" class="text-xs bg-blue-700 px-3 py-1.5 rounded-xl font-semibold hover:bg-blue-800 transition">Kembali</a>
            </div>

            <div class="flex-1 p-6 overflow-y-auto space-y-4 bg-slate-50/60" id="chatContainer">
                @forelse($chats as $chat)
                    @if($chat->pengirim == 'user')
                        <div class="flex flex-col items-end space-y-1">
                            <div class="bg-blue-600 text-white rounded-2xl rounded-tr-none px-4 py-2.5 max-w-md shadow-sm text-sm">
                                <p>{{ $chat->pesan }}</p>
                                @if($chat->bukti_foto)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/bukti_komplain/' . $chat->bukti_foto) }}" class="rounded-xl max-w-xs border border-blue-500 shadow" alt="Bukti Komplain">
                                    </div>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400 mr-1">{{ $chat->created_at->format('H:i') }}</span>
                        </div>
                    @else
                        <div class="flex flex-col items-start space-y-1">
                            <span class="text-[10px] font-bold text-slate-500 ml-1">👮 Admin CleanClick</span>
                            <div class="bg-white text-slate-800 border border-slate-200 rounded-2xl rounded-tl-none px-4 py-2.5 max-w-md shadow-sm text-sm">
                                <p>{{ $chat->pesan }}</p>
                            </div>
                            <span class="text-[10px] text-slate-400 ml-1">{{ $chat->created_at->format('H:i') }}</span>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-20 text-slate-400 text-sm">
                        👋 Belum ada obrolan. Silakan kirimkan keluhan Anda di bawah ini jika ada kendala.
                    </div>
                @endforelse
            </div>

            <div class="p-4 bg-white border-t border-slate-200">
                <form action="{{ route('user.komplain.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-2xl p-2 focus-within:border-blue-500 transition">
                        
                        <label class="cursor-pointer p-2 hover:bg-slate-200 rounded-xl transition group relative" title="Upload Foto Bukti">
                            <span class="text-xl">📷</span>
                            <input type="file" name="bukti_foto" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </label>

                        <input type="text" name="pesan" placeholder="Tulis rincian keluhan Anda di sini..." class="w-full bg-transparent text-sm p-2 outline-none text-slate-800">
                        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-sm transition">
                            Kirim 🚀
                        </button>
                    </div>
                    
                    <div id="filePreview" class="text-xs text-slate-500 px-2 hidden flex items-center gap-1">
                        🖼️ File terpilih: <span id="fileName" class="font-bold text-blue-600"></span>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <script>
        // Auto Scroll Chat box ke bagian paling bawah otomatis
        const objDiv = document.getElementById("chatContainer");
        objDiv.scrollTop = objDiv.scrollHeight;

        function previewImage(input) {
            const previewDiv = document.getElementById('filePreview');
            const nameSpan = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                nameSpan.innerText = input.files[0].name;
                previewDiv.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>