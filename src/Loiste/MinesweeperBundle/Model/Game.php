<?php

namespace Loiste\MinesweeperBundle\Model;

/**
 * This class represents a game model.
 */
class Game
{
    /**
     * A two dimensional array of game objects.
     *
     * E.x.: $gameArea[3][2] instance of GameObject
     *
     * @var array
     */
    public $gameArea;

    public function __construct()
    {
        // Upon constructing a new game instance, setup an empty game area.
        $this->gameArea = array();

        for ($row = 0; $row < 10; $row++) {

            $temp = array();
            for ($column = 0; $column < 20; $column++) {
                $temp[] = new GameObject(mt_rand(0, 1)); // Randomize the game object type.
            }
            $this->gameArea[] = $temp;
        }
    }
	
	public function surrounds($row, $column)
	{
		// This function returns the tiles surrounding given coordinates
		$y = $row;
		$x = $column;
		// Until we figure out some cool script for this
		// We just list the surrounding tiles' coordinates
		// TODO: test coordinates
		$tiles = array("-1,+1", "0,+1", "+1,+1", 
						"-1,0", "+1,0", 
						"-1,-1", "0,-1", "+1,-1");
		
		// And startign from x-axis
		if($x == 0)
		{
			// We go through the tiles, and remove all the tiles that aren't surrounding given tile coordinate
			foreach($tiles as $pos => $tile)
			{
				$match = strpos($tile, "-1,");
				if($match !== false) { unset($tiles[$pos]); }
			}
		}
		elseif($x == 19)
		{
			foreach($tiles as $pos => $tile)
			{
				$match = strpos($tile, "+1,");
				if($match !== false) { unset($tiles[$pos]); }
			}
		}

		if($y == 0)
		{
			foreach($tiles as $pos => $tile)
			{
				$match = strpos($tile, ",-1");
				if($match !== false) { unset($tiles[$pos]); }
			}
		}
		elseif($y == 9)
		{
			foreach($tiles as $pos => $tile)
			{
				$match = strpos($tile, ",+1");
				if($match !== false) { unset($tiles[$pos]); }
			}
		}
	
		return $tiles;
	}

}