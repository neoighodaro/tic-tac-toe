<?php

use App\Exceptions\InvalidTilePositionException;

use App\Game\TilePosition;

class TilePositionTest extends TestCase
{
    /** @test */
    public function it_throws_error_on_invalid_position()
    {
        $this->expectException(InvalidTilePositionException::class);

        new TilePosition(10);
    }

    /** @test */
    public function it_fetches_the_correct_position()
    {
        $tilePosition = new TilePosition(9);

        $this->assertEquals(9, $tilePosition->getPosition());
    }
}
