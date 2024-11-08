<?php

namespace Battleship;

use InvalidArgumentException;

class GameController
{
    public static function checkIsHit(array $fleet, $shot)
    {
        if ($fleet == null) {
            throw new InvalidArgumentException("ships is null");
        }

        if ($shot == null) {
            throw new InvalidArgumentException("shot is null");
        }

        foreach ($fleet as $ship) {
            foreach ($ship->getPositions() as $position) {
                if ($position == $shot) {
                    $position->setIsHit(true);

                    return [true, $ship];
                }
            }
        }

        return [false, null];
    }

    public static function randomizeShipPosition($ship)
    {
        $horizontal = (bool)rand(0, 1);

        $size = count(Letter::$letters);

        if ($horizontal) {
            // losujemy punkt, od którego będziemy rysować statek w lewo
            $letter_index = rand($ship->getSize(), 8); // indeksowane od jedynki
            $digit = rand(1, 8);

            for ($i = $letter_index-$ship->getSize(); $i < $letter_index; $i++) {
                $letter = Letter::$letters[$i];
                $ship->addPosition($letter . $digit);
            }
        } else {
            // losujemy punkt, od którego będizemy rosować statek w górę
            $letter_index = rand(1, 8); // indeksowane od jedynki
            $digit = rand($ship->getSize(), 8);

            $letter = Letter::$letters[$letter_index-1];
            for ($i = $digit-$ship->getSize(); $i < $digit; $i++) {
                $ship->addPosition($letter . ($i+1));
            }
        }
    }

    public static function getShipsLeft(array $fleet)
    {
        return array_filter($fleet, function ($ship) {
            return !$ship->isSunk();
        });
    }

    public static function initializeShips()
    {
        return array(
            new Ship("Aircraft Carrier", 5, Color::CADET_BLUE),
            new Ship("Battleship", 4, Color::RED),
            new Ship("Submarine", 3, Color::CHARTREUSE),
            new Ship("Destroyer", 3, Color::YELLOW),
            new Ship("Patrol Boat", 2, Color::ORANGE),
        );
    }

    public static function isShipValid($ship)
    {
        return count($ship->getPositions()) == $ship->getSize();
    }

    public static function getRandomPosition()
    {
        $rows = 8;
        $lines = 8;

        $letter = Letter::value(random_int(0, $lines - 1));
        $number = random_int(0, $rows - 1);

        return new Position($letter, $number);
    }
}
