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
        $allSeasons = Season::get();
        $season = Season::where('active', true)->first();

        // All players, sorted and filtered
        $players = Player::with(['predictions.event'])
            ->get()
            ->map(function ($player) use ($season) {
                $seasonPredictions = $player->predictions->filter(function ($prediction) use ($season) {
                    return $prediction->event->season_id === $season->id;
                });

                $player->season_points = $seasonPredictions->sum('points_awarded');
                return $player;
            })
            ->filter(fn($player) => $player->season_points > 0)
            ->sortByDesc('season_points')
            ->values();

        // All events that have predictions, for breakdown
        $events = Event::where('season_id', $season->id)
            ->where('archived', false)
            ->whereHas('predictions')
            ->with(['predictions.player'])
            ->orderBy('date')
            ->get();

        // Only the latest three events, for a separate “latest races” section
        $recentEvents = Event::where('season_id', $season->id)
            ->where('archived', false)
            ->whereHas('predictions')
            ->with(['predictions.player'])
            ->latest('date')
            ->take(3)
            ->get()
            ->sortBy('date');

        // Build chart labels (names of all events)
        $labels = $events->pluck('name');

        // Build a dataset per player: cumulative points over all events
        $datasets = $players->map(function ($player) use ($events) {
            $sum = 0;
            $data = [];
            foreach ($events as $event) {
                $prediction = $event->predictions->firstWhere('player_id', $player->id);
                $points = $prediction?->points_awarded ?? 0;
                $sum += $points;
                $data[] = $sum;
            }
            return [
                'label' => $player->name,
                'data' => $data,
                'fill' => false,
                'borderColor' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                'tension' => 0.3,
            ];
        })->toArray();

        // Pass recentEvents for latest races display
        return view('leaderboard', compact('players', 'events', 'labels', 'datasets', 'recentEvents', 'allSeasons', 'season'));
    }

    public function showSeason(Season $season)
    {
        // 1) Fetch all events in this season, un‐archived, that have predictions
        $events = Event::where('season_id', $season->id)
            ->where('archived', false)
            ->whereHas('predictions')
            ->with(['predictions.player', 'qualifyingPositions'])
            ->orderBy('date')
            ->get();

        // 2) Compute each player’s total season points
        $players = Player::with('predictions.event')
            ->get()
            ->map(function ($player) use ($season) {
                $points = $player->predictions
                    ->filter(fn($p) => $p->event->season_id === $season->id)
                    ->sum('points_awarded');
                $player->season_points = $points;
                return $player;
            })
            ->filter(fn($p) => $p->season_points > 0)
            ->sortByDesc('season_points')
            ->values();

        // 3) Pass to a view (e.g. leaderboard/season.blade.php)
        return view('leaderboard.season', [
            'season'  => $season,
            'events'  => $events,
            'players' => $players,
        ]);
    }
}