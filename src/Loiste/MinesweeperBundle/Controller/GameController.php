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
		}
		else
		{
			// TODO: I think we could do without passing the game variable
			// Also: expanding reveal
			// Logically it should go like this:
			// get the surrounding tiles->foreach(surroundingTile as tile)->calculateMines(tile)
			// That's slighlty redundant, but could be "idiot proof" solution.
			// ideal solution would be one function-one loop
			/* And that solution could be made by expanding coordinates-array */
			// $surroundingPositions = $game->surrounds($row, $column);
			$mines = $this->calculateMines($game, $row, $column);
			$game->gameArea[$row][$column]->setNumber($mines);
			$game->gameArea[$row][$column]->setEmpty();
			print_r($game->gameArea[$row][$column]);
		}

        return $this->render('LoisteMinesweeperBundle:Default:index.html.twig', array(
            'gameArea' => $game->gameArea
        ));
    }
	
	public function calculateMines($game, $row, $column)
	{
		// get the coordinates of surrounding tiles.
		$surroundingPositions = $game->surrounds($row, $column);
		$mines = 0;
		foreach($surroundingPositions as $coordinate)
		{
			// we can just strip the given string and check whether it's a mine
			$offset = explode(",", $coordinate);
			$x = $row+$offset[0];
			$y = $row+$offset[1];
			if($game->gameArea[$x][$y]->isMine())
			{
				$mines++;
			}
		}
		
		return $mines;

	}
}
