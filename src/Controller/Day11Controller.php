<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day11")
 */
class Day11Controller extends AbstractController
{
    /** @var CalendarServices */
    private $calendarServices;

    /** @var InputReader */
    private $inputReader;

    public function __construct(CalendarServices $calendarServices, InputReader $inputReader)
    {
        $this->calendarServices = $calendarServices;
        $this->inputReader = $inputReader;
    }

    /**
    + @Route("/")
     */
    public function day11()
    {
        $inputs = $this->inputReader->getInput('day11.txt');
        $grid = [];

        $row = 0;
        foreach ($inputs as $input) {
            $grid[$row] = mb_str_split($input);
            $row++;
        }

        $tempGrid = $grid; // we need to change the grid only after looking at all the values
        $occupiedSeats = 0;
        do {
            $hasChanged = false;
            foreach ($grid as $rowNumber => $columns) {
                foreach ($columns as $column => $value) {
                    if ($value === '.') {
                        continue;
                    }
                    $adjacentOccupiedSeats = $this->countOccupiedSeats($rowNumber, $column, $grid);
                    if ($value === 'L' && $adjacentOccupiedSeats === 0) {
                        $tempGrid[$rowNumber][$column] = "#";
                        $hasChanged = true;
                        $occupiedSeats++;
                    } else if ($value === '#' && $adjacentOccupiedSeats >= 4) {
                        $tempGrid[$rowNumber][$column] = "L";
                        $hasChanged = true;
                        $occupiedSeats--;
                    }
                }
            }
            $grid = $tempGrid;
        } while ($hasChanged === true);

        dump($grid);
        dump($occupiedSeats);
        die();

    }

    private function countOccupiedSeats($currentSeatX, $currentSeatY, $grid) {
        $occupiedSeats = 0;
        for($x = $currentSeatX - 1; $x <= $currentSeatX + 1; $x++) {
            for($y = $currentSeatY - 1; $y <= $currentSeatY + 1; $y++) {
                // if the seat exists in our grid and it's occupied, and it's not OUR seat ...
                if (isset($grid[$x][$y]) && $grid[$x][$y] == '#' && ($x.'-'.$y !== $currentSeatX.'-'.$currentSeatY)) {
                    $occupiedSeats++;
                }
            }
        }

        return $occupiedSeats;
    }

    /**
    + @Route("/2")
     */
    public function day11Part2()
    {
    }

}