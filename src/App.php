<?php

use Battleship\GameController;
use Battleship\Position;
use Battleship\Letter;
use Battleship\Color;

class App
{
    private static $myFleet = [];
    private static $enemyFleet = [];
    private static $console;

    public static function run()
    {
        self::$console = new Console();
        self::$console->setForegroundColor(Color::MAGENTA);

        self::$console->println("                                     |__");
        self::$console->println("                                     |\\/");
        self::$console->println("                                     ---");
        self::$console->println("                                     / | [");
        self::$console->println("                              !      | |||");
        self::$console->println("                            _/|     _/|-++'");
        self::$console->println("                        +  +--|    |--|--|_ |-");
        self::$console->println("                     { /|__|  |/\\__|  |--- |||__/");
        self::$console->println("                    +---------------___[}-_===_.'____                 /\\");
        self::$console->println("                ____`-' ||___-{]_| _[}-  |     |_[___\\==--            \\/   _");
        self::$console->println(" __..._____--==/___]_|__|_____________________________[___\\==--____,------' .7");
        self::$console->println("|                        Welcome to Battleship                         BB-61/");
        self::$console->println(" \\_________________________________________________________________________|");
        self::$console->println();
        self::$console->resetForegroundColor();

        self::printGameStepBoundary();

        self::InitializeGame();
        self::StartGame();
    }

    public static function InitializeEnemyFleet()
    {
        $shipsToPlace = GameController::initializeShips();

        foreach ($shipsToPlace as $ship) {
            do {
                GameController::randomizeShipPosition($ship);
            } while ($ship->isPositionValid(self::$enemyFleet));

            print_r($ship->getPosition());

            self::$enemyFleet[] = $ship;
        }

        self::$enemyFleet = GameController::initializeShips();

        array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 4));
        array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 5));
        array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 6));
        array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 7));
        array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 8));

        array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 6));
        array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 7));
        array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 8));
        array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 9));

        array_push(self::$enemyFleet[2]->getPositions(), new Position('A', 3));
        array_push(self::$enemyFleet[2]->getPositions(), new Position('B', 3));
        array_push(self::$enemyFleet[2]->getPositions(), new Position('C', 3));

        array_push(self::$enemyFleet[3]->getPositions(), new Position('F', 8));
        array_push(self::$enemyFleet[3]->getPositions(), new Position('G', 8));
        array_push(self::$enemyFleet[3]->getPositions(), new Position('H', 8));

        array_push(self::$enemyFleet[4]->getPositions(), new Position('C', 5));
        array_push(self::$enemyFleet[4]->getPositions(), new Position('C', 6));
    }

    public static function getRandomPosition()
    {
        $rows = 8;
        $lines = 8;

        $letter = Letter::value(random_int(0, $lines - 1));
        $number = random_int(0, $rows - 1);

        return new Position($letter, $number);
    }

    public static function InitializeMyFleet()
    {
        self::$myFleet = GameController::initializeShips();

        self::$console->println("Please position your fleet (Game board has size from A to H and 1 to 8) :");

        foreach (self::$myFleet as $ship) {

            self::$console->println();
            printf("Please enter the positions for the %s (size: %s)", $ship->getName(), $ship->getSize());

            for ($i = 1; $i <= $ship->getSize(); $i++) {
                printf("\nEnter position %s of %s (i.e A3):", $i, $ship->getSize());
                $input = readline("");
                $ship->addPosition($input);
            }
        }

        self::printGameStepBoundary();
    }

    public static function beep()
    {
        echo "\007";
    }

    public static function InitializeGame()
    {
        self::InitializeMyFleet();
        self::InitializeEnemyFleet();
    }

    public static function StartGame()
    {
        self::$console->println("\033[2J\033[;H");
        self::$console->println("                  __");
        self::$console->println("                 /  \\");
        self::$console->println("           .-.  |    |");
        self::$console->println("   *    _.-'  \\  \\__/");
        self::$console->println("    \\.-'       \\");
        self::$console->println("   /          _/");
        self::$console->println("  |      _  /\" \"");
        self::$console->println("  |     /_\'");
        self::$console->println("   \\    \\_/");
        self::$console->println("    \" \"\" \"\" \"\" \"");

        while (true) {
            self::$console->println("");
            self::$console->println("Player, it's your turn");
            self::$console->println("Enter coordinates for your shot :");
            $position = readline("");

            [$isHit, $ship] = GameController::checkIsHit(self::$enemyFleet, self::parsePosition($position));

            if ($isHit && $ship->isSunk()) {
                $ships = GameController::getShipsLeft(self::$enemyFleet);
                self::beep();

                self::$console->setForegroundColor(Color::ORANGE);

                self::$console->println("You just sank a {$ship->getName()}!");
                self::$console->println("⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣤⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀");
                self::$console->println("⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣄⠈⠛⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀");
                self::$console->println("⠀⠀⠀⣴⣄⠀⢀⣤⣶⣦⣀⠀⠀⣰⣿⣿⡟⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀");
                self::$console->println("⠀⠀⠀⢸⣿⣷⣌⠻⢿⣩⡿⢷⣄⠙⢿⠟⠀⠀⠀⠀⠀⠰⣄⠀⠀⠀⠀⠀⠀⠀");
                self::$console->println("⠀⠀⠀⠈⣿⣿⣿⣷⣄⠙⢷⣾⠟⣷⣄⠀⠀⠀⠀⣠⣿⣦⠈⠀⠀⠀⠀⠀⠀⠀");
                self::$console->println("⠀⠀⠀⠀⢿⣿⣿⣿⣿⣷⣄⠙⢿⣏⣹⣷⣄⠀⢴⣿⣿⠃⠀⠀⠀⠀⢀⡀⠀⠀");
                self::$console->println("⠀⠀⠀⠸⣦⡙⠻⣿⣿⣿⣿⣷⣄⠙⢿⣤⡿⢷⣄⠙⠃⠀⠀⠀⠀⣀⡈⠻⠂⠀");
                self::$console->println("⠀⠀⠀⠀⠈⠻⣦⡈⠻⣿⣿⣿⣿⣷⣄⠙⢷⣾⠛⣷⣄⠀⠀⢀⣴⣿⣿⠀⠀⠀");
                self::$console->println("⠀⠀⠀⠀⠀⠀⠈⠻⣦⡈⠛⠛⠻⣿⣿⣷⣄⠙⠛⠋⢹⣷⣄⠈⠻⠛⠃⠀⠀⠀");
                self::$console->println("⠀⢀⣴⣿⣧⡀⠀⠀⠈⢁⣼⣿⣄⠙⢿⡿⠋⣠⣿⣧⡀⠠⡿⠗⢀⣼⣿⣦⡀⠀");
                self::$console->println("⠀⠟⠛⠉⠙⠻⣶⣤⣶⠟⠋⠉⠛⢷⣦⣴⡾⠛⠉⠙⠻⣶⣤⣶⠟⠋⠉⠛⠻⠀");
                self::$console->println("⠀⣶⣿⣿⣿⣦⣄⣉⣠⣶⣿⣿⣷⣦⣈⣁⣴⣾⣿⣿⣶⣄⣉⣠⣶⣿⣿⣿⣶⠀");
                self::$console->println("⠀⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠀");

                if (count($ships)) {
                    self::$console->println("Ships left: " . implode(
                        ', ',
                        array_map(function ($ship) { return $ship->getName(); }, $ships),
                    ));
                } else {
                    self::$console->setForegroundColor(Color::GREEN);
                    $text = <<<TEXT
__   __                                   _   _
\ \ / /__  _   _       __ _ _ __ ___     | |_| |__   ___
 \ V / _ \| | | |     / _` | '__/ _ \    | __| '_ \ / _ \
  | | (_) | |_| |    | (_| | | |  __/    | |_| | | |  __/
  |_|\___/_\__,_|     \__,_|_|  \___|     \__|_| |_|\___|
__      _(_)_ __  _ __   ___ _ __  | |
\ \ /\ / / | '_ \| '_ \ / _ \ '__| | |
 \ V  V /| | | | | | | |  __/ |    |_|
  \_/\_/ |_|_| |_|_| |_|\___|_|    (_)
TEXT;
                    self::$console->println($text);
                    self::$console->resetForegroundColor();
                    readline("Press ENTER");
                    exit();
                }
            } elseif ($isHit) {
                self::beep();

                self::$console->setForegroundColor(Color::RED);

                self::$console->println("Yeah ! Nice hit !");
                self::$console->println("     _.-^^---....,,--");
                self::$console->println(" _--                  --_");
                self::$console->println("<                        >)");
                self::$console->println("|                         |");
                self::$console->println(" \\._                   _./");
                self::$console->println("    ```--. . , ; .--'''");
                self::$console->println("          | |   |");
                self::$console->println("       .-=||  | |=-.");
                self::$console->println("       `-=#$%&%$#=-'");
                self::$console->println("          | ;  :|");
                self::$console->println(" _____.,-#%&$@%#&#~,._____");
            } else {
                self::$console->setForegroundColor(Color::CADET_BLUE);
                self::$console->println("Miss");
            }

            self::$console->resetForegroundColor();
            self::$console->println();

            $position = self::getRandomPosition();
            [$isHit, $ship] = GameController::checkIsHit(self::$myFleet, $position);
            self::$console->println();
            printf("Computer shoot in %s%s", $position->getColumn(), $position->getRow());
            if ($isHit && $ship->isSunk()) {
                $ships = GameController::getShipsLeft(self::$enemyFleet);
                self::beep();
                self::$console->println("Computer just sank a {$ship->getName()}!");
                self::$console->println("⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣤⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀");
                self::$console->println("⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣄⠈⠛⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀");
                self::$console->println("⠀⠀⠀⣴⣄⠀⢀⣤⣶⣦⣀⠀⠀⣰⣿⣿⡟⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀");
                self::$console->println("⠀⠀⠀⢸⣿⣷⣌⠻⢿⣩⡿⢷⣄⠙⢿⠟⠀⠀⠀⠀⠀⠰⣄⠀⠀⠀⠀⠀⠀⠀");
                self::$console->println("⠀⠀⠀⠈⣿⣿⣿⣷⣄⠙⢷⣾⠟⣷⣄⠀⠀⠀⠀⣠⣿⣦⠈⠀⠀⠀⠀⠀⠀⠀");
                self::$console->println("⠀⠀⠀⠀⢿⣿⣿⣿⣿⣷⣄⠙⢿⣏⣹⣷⣄⠀⢴⣿⣿⠃⠀⠀⠀⠀⢀⡀⠀⠀");
                self::$console->println("⠀⠀⠀⠸⣦⡙⠻⣿⣿⣿⣿⣷⣄⠙⢿⣤⡿⢷⣄⠙⠃⠀⠀⠀⠀⣀⡈⠻⠂⠀");
                self::$console->println("⠀⠀⠀⠀⠈⠻⣦⡈⠻⣿⣿⣿⣿⣷⣄⠙⢷⣾⠛⣷⣄⠀⠀⢀⣴⣿⣿⠀⠀⠀");
                self::$console->println("⠀⠀⠀⠀⠀⠀⠈⠻⣦⡈⠛⠛⠻⣿⣿⣷⣄⠙⠛⠋⢹⣷⣄⠈⠻⠛⠃⠀⠀⠀");
                self::$console->println("⠀⢀⣴⣿⣧⡀⠀⠀⠈⢁⣼⣿⣄⠙⢿⡿⠋⣠⣿⣧⡀⠠⡿⠗⢀⣼⣿⣦⡀⠀");
                self::$console->println("⠀⠟⠛⠉⠙⠻⣶⣤⣶⠟⠋⠉⠛⢷⣦⣴⡾⠛⠉⠙⠻⣶⣤⣶⠟⠋⠉⠛⠻⠀");
                self::$console->println("⠀⣶⣿⣿⣿⣦⣄⣉⣠⣶⣿⣿⣷⣦⣈⣁⣴⣾⣿⣿⣶⣄⣉⣠⣶⣿⣿⣿⣶⠀");
                self::$console->println("⠀⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠀");
                if (count($ships)) {
                    self::$console->println("Ships left: " . implode(
                        ', ',
                        array_map(function ($ship) { return $ship->getName(); }, $ships),
                    ));
                } else {
                    self::$console->setForegroundColor(Color::RED);
                    $text = <<<TEXT
__   __               _           _     _
\ \ / /__  _   _     | | ___  ___| |_  | |
 \ V / _ \| | | |    | |/ _ \/ __| __| | |
  | | (_) | |_| |    | | (_) \__ \ |_  |_|
  |_|\___/ \__,_|    |_|\___/|___/\__| (_)
TEXT;
                    self::$console->println($text);
                    self::$console->resetForegroundColor();
                    readline("Press ENTER");
                    exit();
                }
            } elseif ($isHit) {
                self::beep();

                self::$console->setForegroundColor(Color::RED);

                self::$console->println("  HIT YOUR SHIP!  ");
                self::$console->println("     _.-^^---....,,--");
                self::$console->println(" _--                  --_");
                self::$console->println("<                        >)");
                self::$console->println("|                         |");
                self::$console->println(" \\._                   _./");
                self::$console->println("    ```--. . , ; .--'''");
                self::$console->println("          | |   |");
                self::$console->println("       .-=||  | |=-.");
                self::$console->println("       `-=#$%&%$#=-'");
                self::$console->println("          | ;  :|");
                self::$console->println(" _____.,-#%&$@%#&#~,._____");

            } else {
                self::$console->setForegroundColor(Color::CADET_BLUE);

                self::$console->println("  MISSED!  ");
            }

            self::$console->resetForegroundColor();
            self::printGameStepBoundary();
            //            exit();
        }
    }

    public static function parsePosition($input)
    {
        $letter = substr($input, 0, 1);
        $number = substr($input, 1, 1);

        if (!is_numeric($number)) {
            throw new Exception("Not a number: $number");
        }

        return new Position($letter, $number);
    }

    public static function printGameStepBoundary($txt = "\n\n\n=================================================\n\n\n")
    {
        self::$console->println($txt);
    }
}
