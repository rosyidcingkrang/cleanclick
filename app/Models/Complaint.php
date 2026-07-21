<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaints';
    protected $primaryKey = 'id_komplain';
    
    protected $fillable = [
        'user_id',
        'pesan',
        'bukti_foto',
        'pengirim',
        'status'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}