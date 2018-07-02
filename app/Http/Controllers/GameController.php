<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Game\TileType;
use App\Game\BoardState;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function show(int $id)
    {
        return Game::findOrFail($id);
    }

    public function create(Request $request, BoardState $boardState)
    {
        $unit = array_random([TileType::O, TileType::X]);

        return Game::create([
            'unit' => $unit,
            'state' => $boardState->toArray(),
            'history' => $boardState->getHistory()->toArray(),
        ]);
    }
}
