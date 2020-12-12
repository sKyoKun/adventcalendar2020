<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/1/{file}", defaults={"file"="day11"})
     */
    public function day11($file)
    {
        $inputs = $this->inputReader->getInput($file.'.txt');
        $grid = [];

        $row = 0;
        // split our string in an array with each char in a cell to get our grid
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
                    // people dont seat on the floor
                    if ($value === '.') {
                        continue;
                    }
                    $adjacentOccupiedSeats = $this->countOccupiedSeats($rowNumber, $column, $grid);
                    // if we are on a free seat and nobody is around, we seat !
                    if ($value === 'L' && $adjacentOccupiedSeats === 0) {
                        $tempGrid[$rowNumber][$column] = "#";
                        $hasChanged = true;
                        $occupiedSeats++;
                    } // if a seat is occupied and 4 or more adjacent seats are also occupied, we flee !
                    else if ($value === '#' && $adjacentOccupiedSeats >= 4) {
                        $tempGrid[$rowNumber][$column] = "L";
                        $hasChanged = true;
                        $occupiedSeats--;
                    }
                }
            }
            $grid = $tempGrid;
        } while ($hasChanged === true);

        return new JsonResponse($occupiedSeats, Response::HTTP_OK);
    }

    /**
     * To count occupied seats for part 1, we only have to look at the seats around us in a square of 3x3
     * Do not forget to remove OUR seat !
     * @param $currentSeatX
     * @param $currentSeatY
     * @param $grid
     * @return int
     */
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
     * @Route("/2/{file}", defaults={"file"="day11"})
     */
    public function day11part2($file)
    {
        $inputs = $this->inputReader->getInput($file.'.txt');
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
                    $counter = 0;
                    if ($value === '.') {
                        continue;
                    }
                    // We look in all directions
                    $counter += $this->hasUpleftOccupiedSeat($rowNumber, $column, $grid);
                    $counter += $this->hasUpOccupiedSeat($rowNumber, $column, $grid);
                    $counter += $this->hasUpRightOccupiedSeat($rowNumber, $column, $grid);
                    $counter += $this->hasLeftOccupiedSeat($rowNumber, $column, $grid);
                    $counter += $this->hasRightOccupiedSeat($rowNumber, $column, $grid);
                    $counter += $this->hasDownleftOccupiedSeat($rowNumber, $column, $grid);
                    $counter += $this->hasDownOccupiedSeat($rowNumber, $column, $grid);
                    $counter += $this->hasDownRightOccupiedSeat($rowNumber, $column, $grid);
                    if ($value === 'L' && $counter === 0) {
                        $tempGrid[$rowNumber][$column] = "#";
                        $hasChanged = true;
                        $occupiedSeats++;
                    } else if ($value === '#' && $counter >= 5) {
                        $tempGrid[$rowNumber][$column] = "L";
                        $hasChanged = true;
                        $occupiedSeats--;
                    }
                }
            }
            $grid = $tempGrid;
        } while ($hasChanged === true);

        return new JsonResponse($occupiedSeats, Response::HTTP_OK);
    }

    private function hasUpleftOccupiedSeat($currentSeatX, $currentSeatY, $grid) {
        $posY = $currentSeatY-1;
        for($x = $currentSeatX-1; $x >= 0; $x--) {
            $isOccupied = $this->isSeatOccupied($x,$posY, $grid);
            if($isOccupied !== null) {
                return $isOccupied;
            }
            $posY--;
        }

        return 0;
    }

    private function hasUpOccupiedSeat($currentSeatX, $currentSeatY, $grid) {
        $posY = $currentSeatY;
        for($x = $currentSeatX-1; $x >= 0; $x--) {
            $isOccupied = $this->isSeatOccupied($x,$posY, $grid);
            if($isOccupied !== null) {
                return $isOccupied;
            }
        }

        return 0;
    }

    private function hasUpRightOccupiedSeat($currentSeatX, $currentSeatY, $grid) {
        $posY = $currentSeatY+1;
        for($x = $currentSeatX-1; $x >= 0; $x--) {
            $isOccupied = $this->isSeatOccupied($x,$posY, $grid);
            if($isOccupied !== null) {
                return $isOccupied;
            }
            $posY++;
        }

        return 0;
    }

    private function hasLeftOccupiedSeat($currentSeatX, $currentSeatY, $grid) {
        for($y = $currentSeatY-1; $y >= 0; $y--) {
            $isOccupied = $this->isSeatOccupied($currentSeatX,$y, $grid);
            if($isOccupied !== null) {
                return $isOccupied;
            }
        }

        return 0;
    }

    private function hasRightOccupiedSeat($currentSeatX, $currentSeatY, $grid) {
        for($y = $currentSeatY+1; $y <= count($grid[$currentSeatX]); $y++) {
            $isOccupied = $this->isSeatOccupied($currentSeatX,$y, $grid);
            if($isOccupied !== null) {
                return $isOccupied;
            }
        }

        return 0;
    }

    private function hasDownleftOccupiedSeat($currentSeatX, $currentSeatY, $grid) {
        $posY = $currentSeatY-1;
        for($x = $currentSeatX+1; $x <= count($grid); $x++) {
            $isOccupied = $this->isSeatOccupied($x,$posY, $grid);
            if($isOccupied !== null) {
                return $isOccupied;
            }
            $posY--;
        }

        return 0;
    }

    private function hasDownOccupiedSeat($currentSeatX, $currentSeatY, $grid) {
        $posY = $currentSeatY;
        for($x = $currentSeatX+1; $x <= count($grid); $x++) {
            $isOccupied = $this->isSeatOccupied($x,$posY, $grid);
            if($isOccupied !== null) {
                return $isOccupied;
            }
        }

        return 0;
    }

    private function hasDownRightOccupiedSeat($currentSeatX, $currentSeatY, $grid) {
        $posY = $currentSeatY+1;
        for($x = $currentSeatX+1; $x <= count($grid); $x++) {
            $isOccupied = $this->isSeatOccupied($x,$posY, $grid);
            if($isOccupied !== null) {
                return $isOccupied;
            }
            $posY++;
        }

        return 0;
    }

    private function isSeatOccupied($x, $y, $grid)
    {
        // if it's a seat
        if(isset($grid[$x][$y]) && $grid[$x][$y] !== ".") {
            // and it's occupied, we return 1
            if ($grid[$x][$y] === "#") {
                return 1;
            }
            else {
                return 0;
            }
        }
        // it was the floor ... We should skip it
        return null;
    }

}