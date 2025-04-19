<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Event;

use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{    
    public function index()
    {
        $players = Player::withSum('predictions', 'points_awarded')
            ->orderByDesc('predictions_sum_points_awarded')
            ->get();

        $events = Event::with(['predictions.player'])->orderBy('date')->get();

        return view('leaderboard', compact('players', 'events'));
    }
}