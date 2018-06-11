<?php

use App\Game\BoardState;
use App\Game\TileType;
use App\Game\Tile;
use App\Game\TilePosition;

class BoardStateTest extends TestCase
{
    /** @test */
    public function it_can_add_new_state()
    {
        $state = app(BoardState::class); // BoardState singleton
        $state->add(
            (new Tile)->withType(TileType::O))->withPosition(1)
        );

        $expectedStateArray = [
            [TileType::O, '', ''],
            ['', '', ''],
            ['', '', ''],
        ];

        $this->assertEquals($expectedStateArray, $state->toArray());
    }

    /** @test */
    // public function it_can_determine_if_move_is_valid()
    // {
    //     $this->expectException(InvalidBoardStateException::class);

    //     $state = app(BoardState::class); // BoardState singleton

    //     $state->add([
    //         new Tile(new TileType(TileType::O), new TilePosition(1)),
    //         new Tile(new TileType(TileType::O), new TilePosition(1)),
    //     ]);
    // }
}
