<?php

namespace App\Providers;

use App\Game\BoardState;
use Illuminate\Support\ServiceProvider;
use App\Game\Tile;
use App\Game\Move;
use App\Game\MoveInterface;

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

        $this->app->bind(MoveInterface::class, function () {
            return new Move;
        });
    }
}
