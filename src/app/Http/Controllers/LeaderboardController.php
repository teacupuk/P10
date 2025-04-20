<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Event;
use App\Models\Season;

use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{    
    public function index()
    {
        $players = Player::withSum('predictions', 'points_awarded')
            ->orderByDesc('predictions_sum_points_awarded')
            ->get();
        $season = Season::where('active', true)->first();

        $events = Event::where('season_id', $season->id)
            ->whereHas('predictions')
            ->with(['predictions.player'])
            ->where('archived', false)
            ->orderBy('date')->get();

        return view('leaderboard', compact('players', 'events'));
    }
}