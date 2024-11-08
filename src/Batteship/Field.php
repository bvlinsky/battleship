<?php

namespace Battleship;

use Console;

class Field
{
    public static function render(int $height, int $width, array $fleet, Console $console): void
    {
        $field = [];
        $ships = [];

        foreach ($fleet as $ship) {
            foreach ($ship->getPositions() as $position) {
                $ships[(string) $position] = $ship;
            }
        }

        $console->println();
        $console->print('    ');
        $console->println(implode('  ', Letter::$letters));
        $console->println();

        for ($row = 1; $row <= $height; $row++) {
            $console->print($row . '  ');

            for ($column = 0; $column < $width; $column++) {
                $letter = Letter::value($column);

                if (array_key_exists($letter . $row, $ships)) {
                    $console->setForegroundColor($ships[$letter . $row]->getColor());
                    $console->print('[ ]');
                } else {
                    $console->print('~~~');
                }
                $console->resetForegroundColor();
            }

            $console->println();
        }
    }
}
