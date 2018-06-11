<?php

use App\Game\TileType;
use App\Exceptions\InvalidTileTypeException;

class TileTypeTest extends TestCase
{
    /** @test */
    public function it_throws_exception_on_invalid_type()
    {
        $this->expectException(InvalidTileTypeException::class);

        new TileType('invalid');
    }

    /** @test */
    public function it_sets_the_correct_type()
    {
        $tileType = new TileType(TileType::X);

        $this->assertEquals(TileType::X, $tileType->getType());
    }
}
