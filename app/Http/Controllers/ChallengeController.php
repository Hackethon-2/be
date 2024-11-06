<?php

namespace App\Http\Controllers;

use App\Models\UserPoint;

class ChallengeController extends Controller
{
    // Fungsi untuk menampilkan leaderboard
    public function leaderboard()
    {
        $leaderboard = UserPoint::with('user')
            ->orderBy('points', 'desc')
            ->get(['user_id', 'points']);

        return response()->json($leaderboard);
    }
}
