<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/day17")
 */
class Day17Controller extends AbstractController
{
    const ACTIVE_CELL   = "#";

    const INACTIVE_CELL = ".";

    /** @var CalendarServices */
    private $calendarServices;

    /** @var InputReader */
    private $inputReader;

    /**

     * @param CalendarServices $calendarServices
     * @param InputReader $inputReader
     */
    public function __construct(CalendarServices $calendarServices, InputReader $inputReader)
    {
        $this->calendarServices = $calendarServices;
        $this->inputReader = $inputReader;
    }

    /**
     * @Route("/1/{file}", defaults={"file"="day17"})
     * @param string $file
     * @return JsonResponse
     */
    public function day17(string $file): JsonResponse
    {
        ini_set("memory_limit", "-1");
        $inputs = $this->inputReader->getInput($file.'.txt');

        // $grid[z][x][y] by default z = 0
        $grid = [];
        $yLength = 0;
        foreach ($inputs as $line) {
            $grid[0][] = str_split($line);
            $yLength = count(str_split($line));
        }

        $nbCycles = 6;
        for($i = 0 ; $i < 6; $i++) {
            $saveGrid = $grid;
            $activeCells = 0;
            for($z = -($nbCycles -1); $z <= ($nbCycles -1); $z++) {
                for($x = -($nbCycles -1); $x <= count($grid[0]) + ($nbCycles -1); $x++) {
                    for($y = -($nbCycles -1); $y <= $yLength + ($nbCycles -1); $y++) {
                        if(!isset($saveGrid[$z][$x][$y])) {
                            $grid[$z][$x][$y] = self::INACTIVE_CELL;
                            continue;
                        }
                        $value = $saveGrid[$z][$x][$y];
                        $activeNeighbors = $this->countActiveNeighbors($z, $x, $y, $saveGrid);
                        if(
                            (self::ACTIVE_CELL === $value && (\in_array($activeNeighbors, [2,3]))) ||
                            (self::INACTIVE_CELL === $value && 3 === $activeNeighbors)
                        ) {
                            $grid[$z][$x][$y] = self::ACTIVE_CELL;
                            $activeCells++;
                        } else {
                            $grid[$z][$x][$y] = self::INACTIVE_CELL;
                        }
                    }
                }
            }
        }

        dump($activeCells);die();

        return new JsonResponse([], Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day17"})
     * @param string $file
     * @return JsonResponse
     */
    public function day17part2(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        return new JsonResponse([], Response::HTTP_OK);
    }

    /**
     * @param $zIndex
     * @param $xIndex
     * @param $yIndex
     * @param $grid
     *
     * @return int
     */
    private function countActiveNeighbors($zIndex, $xIndex, $yIndex, $grid): int
    {
        $activeCells = 0;
        for ($z = $zIndex -1 ; $z <= $zIndex+1; $z++) {
            for ($x = $xIndex -1 ; $x <= $xIndex+1; $x++) {
                for ($y = $yIndex -1 ; $y <= $yIndex+1; $y++) {
                    if(isset($grid[$zIndex][$xIndex][$yIndex]) && self::ACTIVE_CELL === $grid[$zIndex][$xIndex][$yIndex]) {
                        $activeCells++;
                    }
                }
            }
        }

        return $activeCells;
    }
}