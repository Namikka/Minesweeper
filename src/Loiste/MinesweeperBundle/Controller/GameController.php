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
			// $game->gameArea[$row][$column]->blowUp();
			print "boom";
			print_r($game->gameArea[$row][$column]);
		}
		else
		{
			$game->gameArea[$row][$column]->setEmpty();
			print_r($game->surrounds($row, $column));
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
	$game = $session->get('game');
	$surroundingPositions = $game->surrounds($row, $column);
	
		foreach($surroundingPositions as $coordinate)
		{
			// for now we use switch-case
			// when the surrounds-function is improved, 
			// we can just strip the given string and chekc whether it's a mine
			// switch()
		}
		
	}
}
