<?php

namespace Battleship;

class Position
{
    /**
     * @var string
     */
    private $column;
    private $row;
    private $isHit = false;

    /**
     * Position constructor.
     * @param string $letter
     * @param string $number
     */
    public function __construct($letter, $number)
    {
        $this->column = Letter::validate(strtoupper($letter));
        $this->row = $number;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function getRow()
    {
        return $this->row;
    }

    public function getIsHit()
    {
        return $this->isHit;
    }

    public function setIsHit($isHit)
    {
        $this->isHit = (bool) $isHit;
    }

    public function __toString()
    {
        return sprintf("%s%s", $this->column, $this->row);
    }


}