<?php

namespace Domino;

/**
 * Class Player
 * @package Domino
 */
class Player
{
    /** @var string  */
    private $name;

    /** @var TileSet  */
    private $tiles;

    /**
     * Player constructor.
     * @param string 
     * @param TileSet 
     */
    public function __construct(string $name, TileSet $tiles)
    {
        $this->name = $name;
        $this->tiles = $tiles;
    }

    /**
     * @param $head
     * @param $tail
     * @return array 
     */
    public function move($head, $tail) {
        $position = TileSet::POSITION_NONE;
        $result = null;

        /** @var Tile $tile */
        foreach ($this->tiles->all() as $key => $tile) {
            $position = $tile->matches($head, $tail);
            if ($position != TileSet::POSITION_NONE) {
                $result = $tile;
                $this->tiles->remove($key);
                break;
            }
        }
        return [$position, $result];
    }

    public function isOutOfTiles() {
        return $this->tiles->isEmpty();
    }

    public function prependTile(Tile $tile) {
        $this->tiles->prepend($tile);
    }

    public function __toString()
    {
        return $this->name;
    }


}