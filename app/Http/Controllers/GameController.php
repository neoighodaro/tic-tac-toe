<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Game\BoardState;
use App\Game\TileType;

class GameController extends Controller
{
    public function create(BoardState $boardState)
    {
        $game = Game::create([
            'state' => $boardState->toArray(),
            'history' => $boardState->getHistory()->toArray(),
            'unit' => array_random([TileType::O, TileType::X]),
        ]);

        return redirect("/game/{$game->id}");
    }

    public function show(int $id, BoardState $boardState)
    {
        $game = Game::select(['id', 'unit', 'state', 'history'])->findOrFail($id);

        $status = (new Api\GameController)->status($id, $boardState);

        $boardState->loadHistory($game->history)->loadState($game->state);

        return view('game', compact('game', 'status'));
    }
}
