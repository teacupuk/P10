<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Route;

Route::get('/', [LeaderboardController::class, 'index'])->name('public.app');

// Season‐specific leaderboard
Route::get('/leaderboard/{season}', [LeaderboardController::class, 'showSeason'])->where('season', '[0-9]{4}')->name('public.season');

// Fallback to active‐season leaderboard
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('public.app');

Route::view('/rules', 'public.rules')->name('public.rules');

Route::get('/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.app');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    # === Events ===
    Route::get('/events', [AdminController::class, 'events'])->name('dashboard.events');
    Route::get('/events/{event}/qualifying', [AdminController::class, 'editQualifying'])->name('dashboard.qualifying.edit');
    Route::post('/events/{event}/qualifying', [AdminController::class, 'updateQualifying'])->name('dashboard.qualifying.update');
    Route::get('/events/{event}/predictions', [AdminController::class, 'editPredictions'])->name('dashboard.predictions.edit');
    Route::post('/events/{event}/predictions', [AdminController::class, 'updatePredictions'])->name('dashboard.predictions.update');
    Route::get('/events/{event}/edit', [AdminController::class, 'editEvent'])->name('dashboard.events.edit');
    Route::patch('/events/{event}', [AdminController::class, 'updateEvent'])->name('dashboard.events.update');
    Route::patch('/events/{event}/archive', [AdminController::class, 'archiveEvent'])->name('dashboard.events.archive');
    Route::post('/events/{event}/restore', [AdminController::class, 'restoreEvent'])->name('dashboard.events.restore');
    Route::get('/events/create', [AdminController::class, 'createEvent'])->name('dashboard.events.create');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('dashboard.events.store');
    Route::get('/seasons/create', [AdminController::class, 'createSeason'])->name('dashboard.seasons.create');
    Route::post('/seasons', [AdminController::class, 'storeSeason'])->name('dashboard.seasons.store');

    # === Players ===
    Route::get('/players', [AdminController::class, 'players'])->name('dashboard.players');
    Route::get('/players/{player}/edit', [AdminController::class, 'editPlayer'])->name('dashboard.players.edit');
    Route::patch('/players/{player}', [AdminController::class, 'updatePlayer'])->name('dashboard.players.update');
    Route::get('/players/create', [AdminController::class, 'createPlayer'])->name('dashboard.players.create');
    Route::post('/players', [AdminController::class, 'storePlayer'])->name('dashboard.players.store');
    Route::delete('/players/{player}', [AdminController::class, 'deletePlayer'])->name('dashboard.players.destroy');
    Route::post('/players/{player}/restore', [AdminController::class, 'restorePlayer'])->name('dashboard.players.restore');

    # === Drivers ===
    Route::get('/drivers', [AdminController::class, 'drivers'])->name('dashboard.drivers');
    Route::get('/drivers/{driver}/edit', [AdminController::class, 'editDriver'])->name('dashboard.drivers.edit');
    Route::patch('/drivers/{driver}', [AdminController::class, 'updateDriver'])->name('dashboard.drivers.update');
    Route::get('/drivers/create', [AdminController::class, 'createDriver'])->name('dashboard.drivers.create');
    Route::post('/drivers', [AdminController::class, 'storeDriver'])->name('dashboard.drivers.store');
    Route::delete('/drivers/{driver}', [AdminController::class, 'deleteDriver'])->name('dashboard.drivers.destroy');
    Route::post('/drivers/{driver}/restore', [AdminController::class, 'restoreDriver'])->name('dashboard.drivers.restore');
});

require __DIR__.'/auth.php';
