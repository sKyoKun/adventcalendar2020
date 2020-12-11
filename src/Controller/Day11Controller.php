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
        $inputs = $this->inputReader->getInput('day11test.txt');
        $grid = [];

        $row = 0;
        foreach ($inputs as $input) {
            $grid[$row] = mb_str_split($input);
            $row++;
        }

        $occupiedSeats = 0;
        do {
            $hasChanged = false;
            foreach ($grid as $rowNumber => $columns) {
                foreach ($columns as $column => $value) {
                    if ($value === '.') {
                        continue;
                    }
                    if ($value === 'L' && $this->countOccupiedSeats($rowNumber, $column, $grid) === 0) {
                        $grid[$rowNumber][$column] = "#";
                        $hasChanged = true;
                        $occupiedSeats++;
                    } else if ($value === '#' && $this->countOccupiedSeats($rowNumber, $column, $grid) >= 4) {
                        $grid[$rowNumber][$column] = "L";
                        $hasChanged = true;
                        $occupiedSeats--;
                    }
                }
            }
        } while ($hasChanged === true);

        dump($occupiedSeats);
        die();

    }

    private function countOccupiedSeats($currentSeatX, $currentSeatY, $grid) {
        $occupiedSeats = 0;
        for($x = $currentSeatX - 1; $x <= $currentSeatX + 1; $x++) {
            for($y = $currentSeatY - 1; $y <= $currentSeatY + 1; $y++) {
                if (isset($grid[$x][$y]) && $grid[$x][$y] == '#') {
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