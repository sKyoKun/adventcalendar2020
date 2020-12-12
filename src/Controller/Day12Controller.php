<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day12")
 */
class Day12Controller extends AbstractController
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
    public function day12()
    {
        $inputs = $this->inputReader->getInput('day12.txt');

        $direction                     = 'E';
        $countDirection['EASTWEST']    = 0;
        $countDirection['NORTHSOUTH']  = 0;

        foreach ($inputs as $string) {
            $command = $string[0];
            $number  = (int)substr($string, 1);

            switch ($command) {
                case 'N':
                case 'S':
                case 'E':
                case 'W':
                    $this->moveInDirection($command, $number, $countDirection);
                    break;
                case 'F':
                    $this->moveInDirection($direction, $number, $countDirection);
                    break;
                case 'L':
                    $newDegrees = $this->convertInvertDegrees($number);
                    $direction  = $this->getNewDirection($direction, $newDegrees);
                    break;
                case 'R':
                    $direction  = $this->getNewDirection($direction, $number);
                    break;

            }
        }

        dump(abs($countDirection['EASTWEST']) + abs($countDirection['NORTHSOUTH']));

        die();

    }

    private function getNewDirection($currentDirection, $degrees) {

        $directions['E'] = [90 => 'S', 180 => 'W', 270 => 'N', 360 => 'E'];
        $directions['S'] = [90 => 'W', 180 => 'N', 270 => 'E', 360 => 'S'];
        $directions['W'] = [90 => 'N', 180 => 'E', 270 => 'S', 360 => 'W'];
        $directions['N'] = [90 => 'E', 180 => 'S', 270 => 'W', 360 => 'N'];

        return $directions[$currentDirection][$degrees];
    }

    private function convertInvertDegrees($invertDegrees) {
        switch($invertDegrees) {
            case 90:
                return 270;
            case 180 :
                return 180;
            case 270:
                return 90;
            case 360:
                return 360;
        }
    }

    private function moveInDirection($direction, $number, &$countDirection) {
        switch ($direction) {
            case 'E':
                $countDirection['EASTWEST'] += $number;
                break;
            case 'W':
                $countDirection['EASTWEST'] -= $number;
                break;
            case 'N':
                $countDirection['NORTHSOUTH'] += $number;
                break;
            case 'S':
                $countDirection['NORTHSOUTH'] -= $number;
                break;
        }
    }


    /**
    + @Route("/2")
     */
    public function day12Part2()
    {
        $inputs = $this->inputReader->getInput('day12.txt');

        die();
    }
}