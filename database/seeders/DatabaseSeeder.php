<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Layanan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Akun Kasir Utama (Admin)
        User::create([
            'name' => 'Faqih Kasir CleanClick',
            'email' => 'admin@cleanclick.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'whatsapp' => '081122334455',
            'alamat' => 'Kantor Utama CleanClick'
        ]);

        // 2. Membuat Akun Contoh Pelanggan (User)
        User::create([
            'name' => 'Rosyid Cingkrang',
            'email' => 'rosyid@gmail.com',
            'password' => bcrypt('user123'),
            'role' => 'user',
            'whatsapp' => '081234567890',
            'alamat' => 'Jl. Pembangunan Mopah Baru No. 1'
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('user123'),
            'role' => 'user',
            'whatsapp' => '085711223344',
            'alamat' => 'Jl. Merdeka No. 10'
        ]);

        // 3. Mengisi Master Data Layanan Laundry sesuai dokumen tugas Anda
        Layanan::create(['nama_layanan' => 'Kiloan Reguler', 'kategori' => 'kiloan_reguler', 'harga_satuan' => 7000, 'satuan' => 'kg', 'estimasi_hari' => 2]);
        Layanan::create(['nama_layanan' => 'Kiloan Ekspres', 'kategori' => 'kiloan_ekspres', 'harga_satuan' => 12000, 'satuan' => 'kg', 'estimasi_hari' => 1]);
        Layanan::create(['nama_layanan' => 'Bedcover', 'kategori' => 'satuan', 'harga_satuan' => 20000, 'satuan' => 'pcs', 'estimasi_hari' => 2]);
        Layanan::create(['nama_layanan' => 'Sepatu', 'kategori' => 'satuan', 'harga_satuan' => 15000, 'satuan' => 'pcs', 'estimasi_hari' => 2]);
        Layanan::create(['nama_layanan' => 'Jas', 'kategori' => 'satuan', 'harga_satuan' => 30000, 'satuan' => 'pcs', 'estimasi_hari' => 3]);
    }
}