<?php

// app/Exports/AduanExport.php
namespace App\Exports;

use App\Models\Aduan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AduanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Muat relasi user dan ganti user_id dengan name
        return Aduan::with('user')->get()->map(function ($aduan) {
            return [
                'ID' => $aduan->id,
                'Judul' => $aduan->judul,
                'Deskripsi' => $aduan->deskripsi,
                'Lokasi' => $aduan->lokasi,
                'Latitude' => $aduan->latitude,
                'Longitude' => $aduan->longitude,
                'Status' => $aduan->status,
                'User' => $aduan->user->name, // Tampilkan nama pengguna
                'kategori' => $aduan->kategori->nama, // Tampilkan nama pengguna
                'Created At' => $aduan->created_at,
                'Updated At' => $aduan->updated_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Deskripsi',
            'Lokasi',
            'Latitude',
            'Longitude',
            'Status',
            'User',
            'kategori_id',
            'Created At',
            'Updated At',
        ];
    }
}
