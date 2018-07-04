<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Game\BoardState;
use Illuminate\Http\Request;
use App\Game\Tile;
use App\Game\MoveInterface;
use App\Http\Controllers\Controller;

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
        return response()->json(Game::findOrFail($id)->toArray());
    }

    /**
     * Displays the status of a game.
     *
     * @param integer $id
     * @param BoardState $boardState
     * @return \Illuminate\Http\Response
     */
    public function status(int $id, BoardState $boardState)
    {
        $game = Game::findOrFail($id);

        $boardState->loadHistory($game->history)->loadState($game->state);

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
            'next_player' => $boardState->nextPlayerUnit() ?: $game->unit
        ];
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

        return response()->json([
            'game' => $game->toArray(),
            'status' => $this->status($id, $boardState)
        ]);
    }

    /**
     * Returns a move from the bot.
     *
     * @param integer $id
     * @param BoardState $boardState
     * @param Tile $tile
     * @param MoveInterface $move
     * @return \Illuminate\Http\Response
     */
    public function autoplay(int $id, BoardState $boardState, Tile $tile, MoveInterface $move)
    {
        $game = Game::findOrFail($id);

        $boardState->loadState($game->state)->loadHistory($game->history);

        $botMove = $move->makeMove($boardState->toArray(), $game->unit);

        $unit = $botMove[2];
        $position = $boardState->getPositionFromCoordinates($botMove[0], $botMove[1]);

        $boardState->add($tile->withPosition($position)->andType($unit))->saveState($game);

        return response()->json([
            'game' => $game->toArray(),
            'status' => $this->status($id, $boardState),
        ]);
    }
}
