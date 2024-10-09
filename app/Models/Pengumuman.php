<?php

// app/Models/Pengumuman.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pengumuman extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['admin_id', 'title', 'content'];

    protected static function booted()
    {
        static::created(function ($pengumuman) {
            // Kirim notifikasi ke semua peternak
            // Misal: Notifikasi logika atau dispatch event untuk mengirim notifikasi
        });
    }
}
