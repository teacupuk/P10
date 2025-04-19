<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Player;
use App\Models\Driver; 
use App\Models\Prediction;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('date', '>=', now())->orderBy('date')->take(3)->get();

        $topPlayers = Player::withSum('predictions', 'points_awarded')
            ->orderByDesc('predictions_sum_points_awarded')
            ->take(5)
            ->get();

        return view('dashboard', compact('upcomingEvents', 'topPlayers'));
    }

    public function events()
    {
        $events = \App\Models\Event::orderBy('date')->get();
        return view('dashboard.events', compact('events'));
    }

    public function editQualifying(Event $event)
    {
        $drivers = Driver::orderBy('name')->get();
        $qualifying = $event->qualifyingPositions;

        return view('dashboard.qualifying', compact('event', 'drivers', 'qualifying'));
    }

    public function updateQualifying(Request $request, Event $event)
    {
        $event->qualifyingPositions()->delete(); // Clear old qualifying data

        foreach ($request->positions as $position => $driverId) {
            if ($driverId) {
                $event->qualifyingPositions()->create([
                    'driver_id' => $driverId,
                    'position' => $position,
                ]);
            }
        }

        // Recalculate points after updating qualifying results
        $p10DriverId = $event->qualifyingPositions()->where('position', 10)->value('driver_id');

        // Reset all points for this event
        Prediction::where('event_id', $event->id)->update(['points_awarded' => 0]);

        $predictions = Prediction::where('event_id', $event->id)->get();

        // Try to find exact match for P10
        $winner = $predictions->firstWhere('predicted_driver', $p10DriverId);

        if ($winner) {
            $winner->update(['points_awarded' => 2]);
        } else {
            // Look from P9 up to P1
            for ($pos = 9; $pos >= 1; $pos--) {
                $driverId = $event->qualifyingPositions()->where('position', $pos)->value('driver_id');
                $winner = $predictions->firstWhere('predicted_driver', $driverId);
                if ($winner) {
                    $winner->update(['points_awarded' => 1]);
                    break;
                }
            }
        }

        return redirect()->route('dashboard.events')->with('success', 'Qualifying results updated successfully.');
    }

    public function editPredictions(Event $event)
    {
        $players = Player::orderBy('name')->get();
        $predictions = $event->predictions->keyBy('player_id');

        return view('dashboard.predictions', compact('event', 'players', 'predictions'));
    }

    public function updatePredictions(Request $request, Event $event)
    {
        foreach ($request->predictions as $playerId => $driverId) {
            if ($driverId) {
                Prediction::updateOrCreate(
                    ['event_id' => $event->id, 'player_id' => $playerId],
                    ['predicted_driver' => $driverId]
                );
            }
        }

        return redirect()->route('dashboard.events')->with('success', 'Predictions updated.');
    }
}
