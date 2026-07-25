<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Master Data Layanan Laundry
        $listLayanan = [
            [
                'nama_layanan' => 'Kiloan Reguler', 
                'kategori'     => 'kiloan_reguler', 
                'harga_satuan' => 7000, 
                'satuan'       => 'kg', 
                'estimasi_hari' => 2
            ],
            [
                'nama_layanan' => 'Kiloan Ekspres', 
                'kategori'     => 'kiloan_ekspres', 
                'harga_satuan' => 12000, 
                'satuan'       => 'kg', 
                'estimasi_hari' => 1
            ],
            [
                'nama_layanan' => 'Bedcover',       
                'kategori'     => 'satuan',         
                'harga_satuan' => 20000, 
                'satuan'       => 'pcs', 
                'estimasi_hari' => 2
            ],
            [
                'nama_layanan' => 'Sepatu',         
                'kategori'     => 'satuan',         
                'harga_satuan' => 15000, 
                'satuan'       => 'pcs', 
                'estimasi_hari' => 2
            ],
            [
                'nama_layanan' => 'Jas',            
                'kategori'     => 'satuan',         
                'harga_satuan' => 30000, 
                'satuan'       => 'pcs', 
                'estimasi_hari' => 3
            ],
        ];

        foreach ($listLayanan as $layanan) {
            Layanan::firstOrCreate(
                ['nama_layanan' => $layanan['nama_layanan']],
                $layanan
            );
        }
    }
}