<?php

namespace App\Game;

class Move implements MoveInterface
{
    /**
     * Makes a move using the $boardState
     * $boardState contains 2 dimensional array of the game field
     * X represents one team, O - the other team, empty string means field is not yet taken.
     * example
     * [['X', 'O', '']
     * ['X', 'O', 'O']
     * ['', '', '']]
     * Returns an array, containing x and y coordinates for next move, and the unit that now occupies it.
     * Example: [2, 0, 'O'] - upper right corner - O player
     *
     * @param  array $boardState Current board state
     * @param  string $playerUnit Player unit representation
     * @return array
     */
    public function makeMove($boardState, $playerUnit = 'X')
    {
        $available = [];

        $available = app(BoardState::class)->loadState($boardState)->availablePositions();

        $botUnit = ($playerUnit === TileType::X ? TileType::O : TileType::X);

        $tilePosition = new TilePosition(array_random($available));
        $x = $tilePosition->xAxis();
        $y = $tilePosition->yAxis();

        return [$x, $y, $botUnit];
    }
}
