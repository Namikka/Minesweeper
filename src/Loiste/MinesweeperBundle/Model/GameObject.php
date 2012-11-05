<?php

namespace Loiste\MinesweeperBundle\Model;

/**
 * This class represents a game object.
 */
class GameObject
{
    const TYPE_UNDISCOVERED = 0;
    const TYPE_MINE = 1;
    const TYPE_EMPTY = 2; // Discovered.
    const TYPE_NUMBER = 3; // Discovered.
    const TYPE_EXPLOSION = 4; // Damn we hit a mine!
    const TYPE_MINE_DISCOVERED = 5;

    public $type;
	public $surroundingMines;
	public $blown;
	
    public function __construct($type = 0)
    {
        $this->type = $type;
    }

    public function isMine()
    {
        return $this->type === GameObject::TYPE_MINE;
    }
	
    public function blowUp()
    {
        $this->blown = 1;
		$this->type = GameObject::TYPE_EXPLOSION;
	}	
	
    public function setEmpty()
    {
		$this->type = GameObject::TYPE_EMPTY;
	}

    public function isNumber()
    {
        return $this->type === GameObject::TYPE_NUMBER;
    }

    public function isEmpty()
    {
        return $this->type === GameObject::TYPE_EMPTY;
    }

    /**
     * Sets the number of mines around this cell.
     */
    public function setNumber($mines)
    {
		$this->surroundingMines = $mines;
		// return $this->surroundingMines;
    }

    /**
     * Returns the number of mines around this cell.
     */	
    public function getNumber()
    {
		// $this->surroundingMines = 1;
		return $this->surroundingMines;
    }
	
}