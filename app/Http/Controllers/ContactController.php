<?php
namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validasi data yang diterima dari frontend
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email',
            'kota' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'pesan' => 'required|string',
        ]);

        // Simpan data pesan ke dalam database
        $contactMessage = ContactMessage::create($validatedData);

        // Menyusun pesan untuk dikirimkan ke WhatsApp
        $message = urlencode(
            "Nama: {$validatedData['nama_lengkap']}\n" .
            "Alamat: {$validatedData['alamat']}\n" .
            "Email: {$validatedData['email']}\n" .
            "Kota: {$validatedData['kota']}\n" .
            "No. Telepon: {$validatedData['no_telp']}\n" .
            "Pesan: {$validatedData['pesan']}"
        );

        // Nomor WhatsApp admin atau tim yang menerima pesan
        $whatsappNumber = '6285156416448'; // Ganti dengan nomor WhatsApp tujuan

        // Membuat URL WhatsApp API untuk mengarahkan ke chat
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$message}";

        // Mengembalikan respons dengan redirect ke URL WhatsApp
        return response()->json([
            'message' => 'Pesan Anda telah terkirim!',
            'whatsapp_url' => $whatsappUrl, // URL yang akan dipakai frontend
        ]);
    }
}
