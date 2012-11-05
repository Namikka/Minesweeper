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
			$game->gameArea[$row][$column]->setEmpty();
			$surroundingPositions = $game->surrounds($row, $column);
			$mines = 0;
			foreach($surroundingPositions as $coordinate)
			{
				// we can just strip the given string and check whether it's a mine
				$offset = explode(",", $coordinate);
				$x = $offset[0]+$column;
				$y = $offset[1]+$row;
				
				if($game->gameArea[$y][$x]->isMine())
				{
					$mines++;
				} 
			}
			$game->gameArea[$row][$column]->setNumber($mines);
			print_r($game->gameArea[$row][$column]);

			// varmaan tähä sit vois laittaa sen logiikan joka laskee et kuin monta miinaa on lähistöllä.
			// Tai sit tehään sille oma funktionsa jottei tästä tulis niin saatanan pitkä
		}
		// Documentation for self:
		// $game is just a bunch of arrays within objects, representing the gamearea.
		// array has number 0-3, and more info on what those things are can be found from GameObject.php-file
		// So, like, $game->gameArea[0][2] is the first rows third tile, and it's usually an integer
		
        return $this->render('LoisteMinesweeperBundle:Default:index.html.twig', array(
            'gameArea' => $game->gameArea
        ));
    }
	
	public function calculateMines($row, $column)
	{
		$session = getSession();
		$game = $session->get('game');
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
