<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Player;
use App\Models\User;
use App\Models\Driver; 
use App\Models\Prediction;
use Illuminate\Support\Facades\Hash;
use App\Models\Season;
use App\Models\Team;

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

        return view('dashboard.app', compact('upcomingEvents', 'topPlayers'));
    }

    public function events(Request $request)
    {
        $search = $request->input('search');
        $seasonId = $request->input('season');

        $season = Season::find($seasonId) 
            ?? Season::where('active', true)->first();

        $allSeasons = Season::orderByDesc('id')->get();

        $events = Event::where('season_id', $season->id)
            ->where('archived', false)
            ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->orderBy('date')
            ->get();

        $archivedEvents = Event::where('season_id', $season->id)
            ->where('archived', true)
            ->orderBy('date')
            ->get();

        return view('dashboard.events', compact('archivedEvents', 'events', 'search', 'season', 'allSeasons'));
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
            'country_code' => $request->country_code,
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
            'country_code' => $request->country_code,
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
        // Base validation for player name
        $rules = ['name' => 'required|string|max:255'];

        // If no linked user, require email & password
        if (! $player->user) {
            $rules['email']    = 'required|email|unique:users,email';
            $rules['password'] = 'required|confirmed|min:8';
        }
        $data = $request->validate($rules);

        // Update player name
        $player->name = $data['name'];

        // Create and link a new User if none exists
        if (! $player->user) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $player->user()->associate($user);
        } else {
            // If user exists and change-password fields submitted, update password
            if ($request->filled('new_password')) {
                $request->validate([
                    'new_password' => 'required|confirmed|min:8',
                ]);
                $player->user->update([
                    'password' => Hash::make($request->input('new_password')),
                ]);
            }
        }

        // Save player changes (name and/or user_id)
        $player->save();

        // Redirect back to the player management screen with a success message
        return redirect()
            ->route('dashboard.players')
            ->with('success', 'Player updated successfully.');
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

    # ==== Driver Functions ====
    public function drivers(Request $request)
    {
        $search = $request->input('search');
        $showArchived = $request->boolean('show_archived');

        $drivers = Driver::when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when(!$showArchived, function ($query) {
                return $query->where('archived', false);
            })
            ->get();

        return view('dashboard.drivers', compact('drivers', 'search', 'showArchived'));
    }

    // Edit a specific player
    public function editDriver(Driver $driver)
    {
        $teams = Team::orderBy('name')->get();

        return view('dashboard.drivers.edit', compact('driver', 'teams'));
    }

    // Update driver
    public function updateDriver(Request $request, Driver $driver)
    {
        $request->validate([
            'id'          => 'required|integer|unique:drivers,id,' . $driver->id,
            'name'        => 'required|string|max:255',
            'team_id'        => 'required|integer',
            'nationality' => 'required|string|max:255',
        ]);

        // Update driver attributes: id (driver number), name, team_id, nationality
        $driver->update([
            'id'          => $request->input('id'),
            'name'        => $request->input('name'),
            'team_id'     => $request->input('team_id'),
            'nationality' => $request->input('nationality'),
        ]);

        return redirect()->route('dashboard.drivers')->with('success', 'Driver updated.');
    }

    // Show form to create new player
    public function createDriver()
    {
        $teams = Team::orderBy('name')->get();

        return view('dashboard.drivers.create', compact('teams'));
    }

    // Store new player
    public function storeDriver(Request $request)
    {
        $request->validate([
            'id'          => 'required|integer|unique:drivers,id',
            'name'        => 'required|string|max:255',
            'team_id'     => 'required|integer|exists:teams,id',
            'nationality' => 'required|string|max:255',
        ]);

        Driver::create([
            'id'          => $request->id,
            'name'        => $request->name,
            'team_id'     => $request->team_id,
            'nationality' => $request->nationality,
        ]);

        return redirect()
            ->route('dashboard.drivers')
            ->with('success', 'Driver created.');
    }

    // Delete player (archive instead of delete)
    public function deleteDriver(Driver $driver)
    {
        $driver->update(['archived' => true]);

        return redirect()->route('dashboard.drivers')->with('success', 'Driver archived.');
    }

    public function restoreDriver(Driver $driver)
    {
        $driver->update(['archived' => false]);

        return redirect()->route('dashboard.drivers')->with('success', 'Driver restored.');
    }
}
