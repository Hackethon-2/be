<?php

// app/Models/Challenge.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    // Menentukan atribut yang dapat diisi
    protected $fillable = [
        'name',
        'description',
        'points'
    ];

    // Relasi ke UserPoint, setiap tantangan bisa memiliki banyak poin pengguna
    public function userPoints()
    {
        return $this->hasMany(UserPoint::class);
    }
}
