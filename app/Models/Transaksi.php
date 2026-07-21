<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected static function boot()
    {
        parent::boot();

        // Otomatisasi logika bisnis tepat sebelum data disimpan ke PostgreSQL
        static::creating(function ($transaksi) {
            // 1. Otomatisasi Generate Nomor Nota (Format: INV-YYYYMMDD-01)
            $tglStr = now()->format('Ymd');
            $urutan = self::where('tanggal', now()->format('Y-m-d'))->count() + 1;
            $transaksi->no_nota = 'INV-' . $tglStr . '-' . str_pad($urutan, 2, '0', STR_PAD_LEFT);

            // 2. Otomatisasi Mengambil Data dari Master Layanan Terpilih
            $layanan = \App\Models\Layanan::find($transaksi->id_layanan);
            $transaksi->harga_satuan = $layanan->harga_satuan;
            
            // 3. Otomatisasi Hitung Total Harga
            $transaksi->total_harga = $transaksi->quantity * $layanan->harga_satuan;

            // 4. Otomatisasi Hitung Estimasi Hari Selesai Cucian
            $transaksi->estimasi_selesai = now()->addDays($layanan->estimasi_hari);
        });
    }

    // Definisi Relasi (Foreign Key)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }
}