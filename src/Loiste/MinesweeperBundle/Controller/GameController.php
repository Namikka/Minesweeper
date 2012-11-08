<?php

namespace Loiste\MinesweeperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Loiste\MinesweeperBundle\Model\Game;

class GameController extends Controller
{
    public function startAction()
    {
		print "startAction";
        // Setup an empty game. To keep things very simple for candidates, we just store info on the session.
        $game = new Game();
		
        $session = new Session();
        $session->start();
        $session->set('game', $game);

		
        return $this->render('LoisteMinesweeperBundle:Default:index.html.twig', array(
            'gameArea' => $game->gameArea
        ));
    }

    public function makeMoveAction()
    {
        $row = $this->getRequest()->get('row'); // Retrieves the row index.
        $column = $this->getRequest()->get('column'); // Retrieves the column index.
		
		$session = new Session();
		$session->start();
		$game = $session->get('game'); /** @var $game Game */

		if($game->gameArea[$row][$column]->isMine())
		{
			print "boom";
			$game->gameArea[$row][$column]->blowUp();
			// print_r($game->surrounds($row, $column));
		}
		else
		{
			$game->gameArea[$row][$column]->setEmpty();
			// TODO: expanding reveal
			// So, when checking out surrounding tiles, if there's an empty tile
			// We keep looping, and we stop when...
			// we have to rethink the logic here:
			// If this is empty and has no mines around it, it's empty.
			// If this is empty and has mines around it, it's a number.
			// Loop should be kept going by adding coordinates of empty tiles to surroundingPositions array

			$surroundingTiles = $game->surrounds($row, $column);
			foreach($surroundingTiles as $coordinate)
			{
				
					$offset = explode(",", $coordinate);
					$x = $column+$offset[0]; // Column
					$y = $row+$offset[1]; // Row
				if(!$game->gameArea[$y][$x]->isMine())
				{
					// TODO: I think we could do without passing the game variable to calculateMines
					$mines = $this->calculateMines($game, $y, $x);
					$game->gameArea[$y][$x]->setNumber($mines);
				}
				elseif($game->gameArea[$y][$x]->isNumber())
				{
					$game->gameArea[$row][$column]->setEmpty();
				}
			}

		}

        return $this->render('LoisteMinesweeperBundle:Default:index.html.twig', array(
            'gameArea' => $game->gameArea
        ));
    }
	
	public function calculateMines($game, $row, $column)
	{
		// get the coordinates of surrounding tiles.
		$surroundingPositions = $game->surrounds($row, $column);
		
		// IF the current tile has a mine, we'll just add that to count
		if($game->gameArea[$row][$column]->isMine()) 
		{
			$mines = 1;
		}
		else
		$mines = 0;
		foreach($surroundingPositions as $coordinate)
		{
			// we can just strip the given string and check whether it's a mine
			$offset = explode(",", $coordinate);
			$x = $column+$offset[0];
			$y = $row+$offset[1];
			if($game->gameArea[$x][$y]->isMine())
			{
				$mines++;
			}
		}
		
		return $mines;

	}
}
