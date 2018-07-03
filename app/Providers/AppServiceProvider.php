<?php

namespace App\Providers;

use App\Game\BoardState;
use Illuminate\Support\ServiceProvider;
use App\Game\Tile;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BoardState::class, function () {
            return new BoardState;
        });

        $this->app->bind(Tile::class, function () {
            return new Tile;
        });
    }
}
