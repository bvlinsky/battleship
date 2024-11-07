<?php

namespace Battleship;

class Number
{
    public static $numbers = array('1', '2', '3', '4', '5', '6', '7', '8');

    public static function value($index)
    {
        return self::$numbers[$index];
    }

    public static function validate($number): string
    {
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("Not a number: $number");
        }

        if (!in_array($number, self::$numbers)) {
            throw new \InvalidArgumentException("Number outside range");
        }

        return $number;
    }
}