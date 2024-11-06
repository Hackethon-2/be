<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AduanStatusUpdated;
use App\Models\Kategori;
use App\Models\UserPoint;

class AduanController extends Controller
{
    // Store a new aduan
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'kategori_id' => 'required|exists:kategoris,id',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,mp4|max:2048', // validate file type and size
        ]);

        $filePath = null;

        // Check if file is present and store it
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads/aduan_files', 'public');
        }

        $aduan = Aduan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kategori_id' => $request->kategori_id,
            'user_id' => Auth::id(),
            'file' => $filePath, // Save file path to database
        ]);
        $aduan->load('kategori');

        return response()->json(['message' => 'Aduan created successfully', 'aduan' => $aduan], 201);
    }

    // Get all aduans for authenticated user
    public function index()
    {
        $user = Auth::user();

        // Pastikan ada user yang terautentikasi
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Ambil semua aduan milik pengguna yang sedang login dan muat relasi kategori
        $aduans = $user->aduan()->with('kategori')->get();

        return response()->json($aduans);
    }

    // Update aduan status
    public function update(Request $request, $kek, $status)
    {
        // Authenticate user (admin)
        $admin = Auth::user();

        // Check if the authenticated user is an admin
        if (!$admin || $admin->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized access. Only admins can update status.'], 403);
        }

        // Find the Aduan by ID
        $aduan = Aduan::find($kek);

        // Check if the Aduan exists
        if (!$aduan) {
            return response()->json(['error' => 'Aduan not found'], 404);
        }

        // Get the current status before updating
        $oldStatus = $aduan->status;

        // Update the status if it is different from the current status
        if ($oldStatus !== $status) {
            $aduan->status = $status;
            $aduan->save();

            // Jika status "Selesai", tambahkan poin untuk user
            if ($status === 'Selesai') {
                $this->updatePointsOnAduanCompletion($aduan->id);
            }

            // Send an email notification to the user who created the Aduan
            $aduanUser = $aduan->user; // Assuming `user` relationship exists in Aduan model
            if ($aduanUser && $aduanUser->email) {
                try {
                    Mail::to($aduanUser->email)
                        ->send(new AduanStatusUpdated($aduan, $oldStatus, $admin->email, $admin->name));

                    return response()->json(['message' => 'Aduan status updated successfully, and notification email sent.', 'aduan' => $aduan]);
                } catch (\Exception $e) {
                    // Handle email sending error
                    return response()->json(['message' => 'Aduan status updated, but failed to send email notification.', 'error' => $e->getMessage()], 500);
                }
            }

            return response()->json(['message' => 'Aduan status updated successfully.', 'aduan' => $aduan]);
        }

        return response()->json(['message' => 'No changes made. The status is already set to the given value.', 'aduan' => $aduan]);
    }

    // Fungsi untuk memperbarui poin pengguna jika aduan selesai
    public function updatePointsOnAduanCompletion($aduanId)
    {
        $aduan = Aduan::find($aduanId);

        if ($aduan && $aduan->status === 'Selesai') {
            // Jika status aduan selesai, beri poin ke user
            $this->addPointsToUser($aduan->user_id);
        }
    }

    // Fungsi untuk menambahkan poin ke user
    public function addPointsToUser($userId, $points = 10)
    {
        $userPoint = UserPoint::firstOrCreate(['user_id' => $userId]);
        $userPoint->increment('points', $points);
    }

    public function filterByLocation(Request $request)
    {
        $request->validate([
            'lokasi' => 'required|string|max:255',
        ]);

        $lokasi = $request->lokasi;


        // Adjust query to handle COUNT and grouping correctly
        $aduans = Aduan::select('aduans.*')
            ->selectRaw('(SELECT COUNT(*) FROM aduans WHERE lokasi = ?) as complaint_count', [$lokasi])
            ->where('lokasi', $lokasi)
            ->orderBy('complaint_count', 'DESC')
            ->get();


        return response()->json($aduans);
    }

}
