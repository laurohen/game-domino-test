<?php

namespace Domino;

/**
 * Class Game
 * @package Domino
 */
class Game
{

    /** @var bool */
    private $finished = false;

    /** @var TileSet */
    private $stock;

    /** @var TileSet */
    private $board;

    /** @var array */
    private $players;

    public function play()
    {
        $this->initialize();
        $this->output("Game starting with first tile : {$this->board}");

        while (!$this->finished) {
            /** @var Player $player */
            foreach ($this->players as $player) {
                try {
                    
                    $this->turn($player);
                    $this->output("Board is now {$this->board}.");
                    $this->checkForWinner($player);
                    $this->checkForTilesInStock();

                    if ($this->finished) {
                        break;
                    }
                } catch (\Exception $exception) {
                    $this->output($exception->getMessage());
                }
            }
        }
    }

    /**
     * @param Player $player
     * @throws \Exception
     */
    private function turn(Player $player) {
        $tile = null;
        $position = TileSet::POSITION_NONE;
        
        while (empty($tile) && !$this->stock->isEmpty()) {

            list($position, $tile) = $player->move($this->board->head(), $this->board->tail());

            if (empty($tile)) {

                $tileFromStock = $this->stock->getRandomTile();
                $player->prependTile($tileFromStock);
                $this->output("$player can't play, drawing tile $tileFromStock");
            }
        }

        if (!empty($tile)) {
            $this->output("$player plays --> $tile");
            $this->board->add($position, $tile);
        }
    }


    private function initialize()
    {
       
        $this->stock = new TileSet();
        for ($tail = 0; $tail <= 6; $tail++) {
            for ($head = 0; $head <= $tail; $head++) {
                $tile = new Tile($head, $tail);
                $this->stock->append($tile);
            }
        }


        $this->board = new TileSet();
        $this->board->append($this->stock->getRandomTile());


        $this->players[] = new Player(
            'Lauro',
            new TileSet($this->stock->getRandomTiles(7))
        );
        $this->players[] = new Player(
            'Telmo',
            new TileSet($this->stock->getRandomTiles(7))
        );
    }

    private function checkForWinner(Player $player)
    {
        if ($player->isOutOfTiles()) {
            $this->output("Player $player has won.");
            $this->finished = true;
        }
    }

    private function checkForTilesInStock()
    {
        if ($this->stock->isEmpty()) {
            $this->output("Looks like we are out of stock, nobody wins.");
            $this->finished = true;
        }
    }

    private function output($message)
    {
        echo "$message\n\n";
    }

}