<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Game\TileType;
use App\Game\BoardState;
use Illuminate\Http\Request;
use App\Game\Tile;
use App\Game\MoveInterface;

class GameController extends Controller
{
    /**
     * Displays a single resource
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return Game::findOrFail($id);
    }

    /**
     * Displays the status of a game.
     *
     * @param integer $id
     * @param Request $request
     * @param BoardState $boardState
     * @return \Illuminate\Http\Response
     */
    public function status(int $id, Request $request, BoardState $boardState)
    {
        $boardState->loadHistory(Game::findOrFail($id)->history);

        $winner = $boardState->checkWinner();

        if ($winner !== false) {
            return [
                'status' => 'GAME_OVER',
                'winner' => $winner
            ];
        }

        $movesAvailable = (9 - $boardState->getHistory()->count());

        if ($movesAvailable === 0 && $winner === false) {
            return ['status' => 'TIE'];
        }

        return [
            'status' => 'IN_PROGRESS',
            'moves_left' => $movesAvailable,
            'next_player' => $boardState->nextPlayerUnit()
        ];
    }

    /**
     * Creates a new resource
     *
     * @param Request $request
     * @param BoardState $boardState
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, BoardState $boardState)
    {
        $unit = array_random([TileType::O, TileType::X]);

        return Game::create([
            'unit' => $unit,
            'state' => $boardState->toArray(),
            'history' => $boardState->getHistory()->toArray(),
        ]);
    }

    /**
     * Updates an existing resource
     *
     * @param integer $id
     * @param Request $request
     * @param BoardState $boardState
     * @param Tile $tile
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request, BoardState $boardState, Tile $tile)
    {
        $data = $this->validate($request, ['position' => 'int|between:0,9']);

        $game = Game::findOrFail($id);

        $tile = $tile->withType($game->unit)->andPosition($data['position']);

        $boardState->loadState($game->state)
            ->loadHistory($game->history)
            ->add($tile)
            ->saveState($game);

        return $game->toArray();
    }

    public function autoplay(int $id, BoardState $boardState, Tile $tile, MoveInterface $move)
    {
        $game = Game::findOrFail($id);

        $boardState->loadState($game->state)->loadHistory($game->history);

        $botMove = $move->makeMove($boardState->toArray(), $game->unit);

        $unit = $botMove[2];
        $position = $boardState->getPositionFromCoordinates($botMove[0], $botMove[1]);

        $boardState->add($tile->withPosition($position)->andType($unit))->saveState($game);

        return $game->toArray();
    }
}
