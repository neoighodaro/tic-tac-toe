<?php

use App\Exceptions\InvalidTilePositionException;

use App\Game\TilePosition;

class TilePositionTest extends TestCase
{
    /** @test */
    public function it_throws_error_on_invalid_position()
    {
        $this->expectException(InvalidTilePositionException::class);

        new TilePosition(0);
        new TilePosition(10);
    }

    /** @test */
    public function it_fetches_the_correct_position()
    {
        $tilePosition = new TilePosition(9);

        $this->assertEquals(9, $tilePosition->getPosition());
    }

    /** @test */
    public function it_can_return_tile_row()
    {
        $tilePosition = new TilePosition(1);
        $this->assertEquals(0, $tilePosition->getRow());

        $tilePosition = new TilePosition(5);
        $this->assertEquals(1, $tilePosition->getRow());

        $tilePosition = new TilePosition(9);
        $this->assertEquals(2, $tilePosition->getRow());
    }

    /** @test */
    public function it_can_return_tile_row_position()
    {
        $tilePosition = new TilePosition(1);
        $this->assertEquals(0, $tilePosition->getRowPosition());

        $tilePosition = new TilePosition(4);
        $this->assertEquals(0, $tilePosition->getRowPosition());

        $tilePosition = new TilePosition(5);
        $this->assertEquals(1, $tilePosition->getRowPosition());

        $tilePosition = new TilePosition(9);
        $this->assertEquals(2, $tilePosition->getRowPosition());
    }
}
