<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens,Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gender',
        'phone',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Implementasi dari JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // User.php
    public function aduan()
    {
        return $this->hasMany(Aduan::class);
    }

    public function hasRole($role)
    {
        return $this->role === $role; // Pastikan ini sesuai dengan struktur database Anda
    }

}
