<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Game\BoardState;
use App\Game\TileType;

class GameController extends Controller
{
    public function show(int $id, BoardState $boardState)
    {
        $game = Game::select(['id', 'unit', 'state', 'history'])->findOrFail($id);

        $boardState->loadHistory($game->history)->loadState($game->state);

        $nextPlayer = $boardState->nextPlayerUnit() ?: array_random([TileType::X, TileType::O]);

        return view('game', compact('game', 'nextPlayer'));
    }
}
