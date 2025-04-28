<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Player;
use App\Models\Driver; 
use App\Models\Prediction;
use App\Models\Season;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('date', '>=', now())
            ->where('archived', false)
            ->orderBy('date')
            ->take(3)
            ->get();

        $topPlayers = Player::withSum('predictions', 'points_awarded')
            ->orderByDesc('predictions_sum_points_awarded')
            ->take(5)
            ->get();

        return view('dashboard', compact('upcomingEvents', 'topPlayers'));
    }

    public function events(Request $request)
    {
        $search = $request->input('search');
        // Determine selected season or fall back to active
        $seasonId = $request->input('season');
        $season = Season::find($seasonId) 
            ?? Season::where('active', true)->first();
        $allSeasons = Season::orderByDesc('id')->get();

        $query = Event::where('season_id', $season->id)
            ->where('archived', false)
            ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->orderBy('date');

        $upcomingEvents = (clone $query)->where('date', '>=', now())->get();
        $pastEvents = (clone $query)->where('date', '<', now())->get();

        $archivedEvents = Event::where('season_id', $season->id)
            ->where('archived', true)
            ->orderBy('date')
            ->get();

        return view('dashboard.events', compact('upcomingEvents', 'pastEvents', 'archivedEvents', 'search', 'season', 'allSeasons'));
    }

    public function createEvent()
    {
        $activeSeason = Season::where('active', true)->first();
        $allSeasons = Season::orderByDesc('id')->get();

        return view('dashboard.events.create', compact('activeSeason', 'allSeasons'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'date'      => 'required|date',
            'is_sprint' => 'sometimes|boolean',
        ]);

        $season = Season::where('active', true)->first();
        Event::create([
            'season_id'  => $request->season_id,
            'name'       => $request->name,
            'date'       => $request->date,
            'is_sprint'  => $request->boolean('is_sprint'),
            'double_points' => $request->boolean('double_points'),
            'archived'   => false,
        ]);

        return redirect()->route('dashboard.events')
                        ->with('success', 'Event created.');
    }

    public function editEvent(Event $event)
    {
        $allSeasons = Season::orderByDesc('id')->get();

        return view('dashboard.events.edit', compact('event', 'allSeasons'));
    }

    public function archiveEvent(Event $event)
    {
        $event->update(['archived' => true]);

        return redirect()->route('dashboard.events')->with('success', 'Event archived.');
    }

    public function restoreEvent(Event $event)
    {
        $event->update(['archived' => false]);

        return redirect()->route('dashboard.events')->with('success', 'Event restored.');
    }

    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'is_sprint' => 'nullable|boolean',
            'double_points' => 'nullable|boolean',
        ]);

        $event->update([
            'season_id'  => $request->season_id,
            'name' => $request->input('name'),
            'date' => $request->input('date'),
            'is_sprint' => $request->boolean('is_sprint'),
            'double_points' => $request->boolean('double_points'),
        ]);

        return redirect()->route('dashboard.events')->with('success', 'Event updated.');
    }

    public function createSeason()
    {
        return view('dashboard.seasons.create');
    }

    public function storeSeason(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|unique:seasons,id',
            'active' => 'nullable|boolean'
        ]);

        Season::create([
            'id' => $request->id,
            'active' => $request->has('active')
        ]);

        return redirect()->route('dashboard.events')->with('success', 'Season created!');
    }

    public function editQualifying(Event $event)
    {
        $drivers = Driver::orderBy('name')->get();
        $qualifying = $event->qualifyingPositions;

        return view('dashboard.qualifying', compact('event', 'drivers', 'qualifying'));
    }

    public function updateQualifying(Request $request, Event $event)
    {
        // Clear old qualifying data
        $event->qualifyingPositions()->delete(); 

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

        // Determine if this event awards double points
        $multiplier = $event->double_points ? 2 : 1;

        // Pulls the predictions for this event
        $predictions = Prediction::where('event_id', $event->id)->get();

        // Try to find exact match for P10
        $winner = $predictions->firstWhere('predicted_driver', $p10DriverId);

        if ($winner) {
            $winner->update(['points_awarded' => 2 * $multiplier]);
        } else {
            // Look from P9 up to P1
            for ($pos = 9; $pos >= 1; $pos--) {
                $driverId = $event->qualifyingPositions()->where('position', $pos)->value('driver_id');
                $winner = $predictions->firstWhere('predicted_driver', $driverId);
                if ($winner) {
                    $winner->update(['points_awarded' => 1 * $multiplier]);
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

    public function players(Request $request)
    {
        $search = $request->input('search');
        $showArchived = $request->boolean('show_archived');

        $players = Player::when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when(!$showArchived, function ($query) {
                return $query->where('archived', false);
            })
            ->get();

        return view('dashboard.players', compact('players', 'search', 'showArchived'));
    }

    // Edit a specific player
    public function editPlayer(Player $player)
    {
        return view('dashboard.players.edit', compact('player'));
    }

    // Update player
    public function updatePlayer(Request $request, Player $player)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $player->update($request->only('name'));

        return redirect()->route('dashboard.players')->with('success', 'Player updated.');
    }

    // Show form to create new player
    public function createPlayer()
    {
        return view('dashboard.players.create');
    }

    // Store new player
    public function storePlayer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Player::create($request->only('name'));

        return redirect()->route('dashboard.players')->with('success', 'Player created.');
    }

    // Delete player (archive instead of delete)
    public function deletePlayer(Player $player)
    {
        $player->update(['archived' => true]);

        return redirect()->route('dashboard.players')->with('success', 'Player archived.');
    }

    public function restorePlayer(Player $player)
    {
        $player->update(['archived' => false]);

        return redirect()->route('dashboard.players')->with('success', 'Player restored.');
    }
}
