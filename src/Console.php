<?php

//use Battleship\Color;

class Console
{
    public function resetForegroundColor()
    {
        echo(Battleship\Color::DEFAULT_GREY);
    }

    public function setForegroundColor($color)
    {
        echo($color);
    }

    public function println($line = "")
    {
        echo "$line\n";
    }
}
