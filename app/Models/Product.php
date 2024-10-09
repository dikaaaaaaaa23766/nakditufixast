<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'name',
        'peternak_id',
        'price',
        'description',
        'stock',
        'whatsapp_number',
    ];

    // Relasi ke pengguna peternak (contoh relasi)
    public function peternak()
    {
        return $this->belongsTo(User::class, 'peternak_id');
    }

    // Relasi ke foto produk
    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }
}
