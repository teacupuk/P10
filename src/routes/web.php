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

Route::view('/rules', 'rules')->name('public.rules');

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
    Route::get('/dashboard/events/{event}/predictions', [AdminController::class, 'editPredictions'])->name('dashboard.predictions.edit');
    Route::post('/dashboard/events/{event}/predictions', [AdminController::class, 'updatePredictions'])->name('dashboard.predictions.update');
    Route::get('/dashboard/events/{event}/edit', [AdminController::class, 'editEvent'])->name('dashboard.events.edit');
    Route::patch('/dashboard/events/{event}', [AdminController::class, 'updateEvent'])->name('dashboard.events.update');
    Route::patch('/dashboard/events/{event}/archive', [AdminController::class, 'archiveEvent'])->name('dashboard.events.archive');
    Route::post('/dashboard/events/{event}/restore', [AdminController::class, 'restoreEvent'])->name('dashboard.events.restore');
    Route::get('/events/create', [AdminController::class, 'createEvent'])->name('dashboard.events.create');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('dashboard.events.store');
    Route::get('/seasons/create', [AdminController::class, 'createSeason'])->name('dashboard.seasons.create');
    Route::post('/seasons', [AdminController::class, 'storeSeason'])->name('dashboard.seasons.store');

    # === Players ===
    Route::get('/dashboard/players', [AdminController::class, 'players'])->name('dashboard.players');
    Route::get('/dashboard/players/{player}/edit', [AdminController::class, 'editPlayer'])->name('dashboard.players.edit');
    Route::patch('/dashboard/players/{player}', [AdminController::class, 'updatePlayer'])->name('dashboard.players.update');
    Route::get('/dashboard/players/create', [AdminController::class, 'createPlayer'])->name('dashboard.players.create');
    Route::post('/dashboard/players', [AdminController::class, 'storePlayer'])->name('dashboard.players.store');
    Route::delete('/dashboard/players/{player}', [AdminController::class, 'deletePlayer'])->name('dashboard.players.destroy');
    Route::post('/dashboard/players/{player}/restore', [AdminController::class, 'restorePlayer'])->name('dashboard.players.restore');
});

require __DIR__.'/auth.php';
