<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    // 1. TAMBAHKAN $fillable UNTUK MENGATASI MassAssignmentException
    protected $fillable = [
        'user_id',
        'id_layanan',
        'no_nota',
        'harga_satuan',
        'quantity',
        'total_harga',
        'status_pembayaran',
        'metode_pembayaran',
        'status_cucian',
        'estimasi_selesai',
        'tanggal',
    ];

    protected static function boot()
    {
        parent::boot();

        // Otomatisasi logika bisnis tepat sebelum data disimpan ke PostgreSQL
        static::creating(function ($transaksi) {
            // Tanggal hari ini
            $today = now()->format('Y-m-d');
            
            if (empty($transaksi->tanggal)) {
                $transaksi->tanggal = $today;
            }

            // 1. Otomatisasi Generate Nomor Nota jika belum diisi (Format: INV-YYYYMMDD-01)
            if (empty($transaksi->no_nota)) {
                $tglStr = now()->format('Ymd');
                $urutan = self::whereDate('created_at', today())->count() + 1;
                $transaksi->no_nota = 'INV-' . $tglStr . '-' . str_pad($urutan, 2, '0', STR_PAD_LEFT);
            }

            // 2. Ambil data Layanan & kalkulasi harga/estimasi jika layanan ditemukan
            if ($transaksi->id_layanan) {
                $layanan = \App\Models\Layanan::find($transaksi->id_layanan);

                if ($layanan) {
                    $transaksi->harga_satuan = $layanan->harga_satuan;

                    // Hitung total harga jika belum dihitung dari controller
                    if (empty($transaksi->total_harga)) {
                        $transaksi->total_harga = $transaksi->quantity * $layanan->harga_satuan;
                    }

                    // Hitung estimasi selesai jika ada atribut estimasi_hari
                    if (isset($layanan->estimasi_hari)) {
                        $transaksi->estimasi_selesai = now()->addDays($layanan->estimasi_hari);
                    }
                }
            }
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