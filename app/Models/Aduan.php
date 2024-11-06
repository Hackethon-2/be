<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'lokasi',   
        'latitude',
        'longitude',
        'status',
        'file',
        'user_id',
        'kategori_id',
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
