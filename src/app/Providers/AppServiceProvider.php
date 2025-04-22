<?php

namespace App\Providers;

use App\Models\Season;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.leaderboard.navigation', function ($view) {
            $seasons = Season::orderBy('id', 'asc')->get();
            $activeSeason = Season::where('active', true)->first();

            $view->with('seasons', $seasons)
                 ->with('season', $activeSeason);
        });
    }
}
