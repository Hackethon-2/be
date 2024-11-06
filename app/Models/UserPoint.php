<?php

// app/Models/UserPoint.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points'
    ];

    // Relasi ke User, setiap UserPoint berhubungan dengan satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
