<?php

use App\Game\Tile;
use App\Game\TileType;
use App\Game\BoardState;
use App\Game\TilePosition;

class BoardStateTest extends TestCase
{
    /** @test */
    public function it_can_add_new_state()
    {
        $state = app(BoardState::class); // BoardState singleton
        $state->add((new Tile)->withType(TileType::O)->withPosition(1));

        $expectedStateArray = [
            [TileType::O, '', ''],
            ['', '', ''],
            ['', '', ''],
        ];

        $this->assertEquals($expectedStateArray, $state->toArray());
    }

    /** @test */
    public function it_can_add_new_states()
    {
        $state = app(BoardState::class); // BoardState singleton
        $state->add([
            (new Tile)->withType(TileType::O)->withPosition(1),
            (new Tile)->withType(TileType::X)->withPosition(2),
            (new Tile)->withType(TileType::O)->withPosition(9)
        ]);

        $expectedStateArray = [
            [TileType::O, TileType::X, ''],
            ['', '', ''],
            ['', '', TileType::O],
        ];

        $this->assertEquals($expectedStateArray, $state->toArray());
    }

    /** @test */
    public function it_can_show_moves_history()
    {
        $state = app(BoardState::class); // BoardState singleton
        $state->add([
            (new Tile)->withType(TileType::O)->withPosition(1),
            (new Tile)->withType(TileType::X)->withPosition(2),
            (new Tile)->withType(TileType::O)->withPosition(9)
        ]);

        $expected = [
            ['x' => 0, 'y' => 0, 'unit' => TileType::O],
            ['x' => 1, 'y' => 0, 'unit' => TileType::X],
            ['x' => 2, 'y' => 2, 'unit' => TileType::O],
        ];

        $this->assertEquals($expected, $state->getMoves()->toArray());
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
