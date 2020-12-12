<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/1/{file}", defaults={"file"="day12"})
     * @param string $file
     * @return JsonResponse
     */
    public function day12(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

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

        return new JsonResponse(abs($countDirection['EASTWEST']) + abs($countDirection['NORTHSOUTH']), Response::HTTP_OK);
    }

    /**
     * Calculate the new direction from old one and rotation
     * @param $currentDirection
     * @param $degrees
     * @return string
     */
    private function getNewDirection($currentDirection, $degrees): string
    {

        $directions['E'] = [90 => 'S', 180 => 'W', 270 => 'N', 360 => 'E'];
        $directions['S'] = [90 => 'W', 180 => 'N', 270 => 'E', 360 => 'S'];
        $directions['W'] = [90 => 'N', 180 => 'E', 270 => 'S', 360 => 'W'];
        $directions['N'] = [90 => 'E', 180 => 'S', 270 => 'W', 360 => 'N'];

        return $directions[$currentDirection][$degrees];
    }

    /**
     * Convert counter clock wise degrees into normal ones
     * @param $invertDegrees
     * @return int
     */
    private function convertInvertDegrees($invertDegrees): int
    {
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

    /**
     * Move the ship/waypoint into direction.
     * Arbitrary values : North and East are positives, South and West negatives.
     * @param $direction
     * @param $number
     * @param $countDirection
     */
    private function moveInDirection($direction, $number, &$countDirection)
    {
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
     * @Route("/2/{file}", defaults={"file"="day12"})
     * @param string $file
     * @return JsonResponse
     */
    public function day12part2(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        // configure the starting points
        $waypoint = [
            'EASTWEST'   => 10,
            'NORTHSOUTH' => 1
        ];

        $ship = [
            'EASTWEST'   => 0,
            'NORTHSOUTH' => 0
        ];

        foreach ($inputs as $string) {
            $command = $string[0];
            $number  = (int)substr($string, 1);

            switch ($command) {
                case 'N':
                case 'S':
                case 'E':
                case 'W':
                    $this->moveInDirection($command, $number, $waypoint);
                    break;
                case 'F':
                    $this->moveShipToWaypoint($number, $ship, $waypoint);
                    break;
                case 'L':
                    $newDegrees = $this->convertInvertDegrees($number);
                    $waypoint  = $this->getNewWaypoint($waypoint, $newDegrees);
                    break;
                case 'R':
                    $waypoint  = $this->getNewWaypoint($waypoint, $number);
                    break;

            }
        }

        return new JsonResponse(abs($ship['EASTWEST']) + abs($ship['NORTHSOUTH']), Response::HTTP_OK);
    }

    /**
     * The ship starts from his point to the waypoint
     * @param $numberTimes
     * @param $ship
     * @param $waypoint
     */
    private function moveShipToWaypoint($numberTimes, &$ship, $waypoint)
    {
        $ship['EASTWEST'] = $ship['EASTWEST'] + ($waypoint['EASTWEST'] * $numberTimes);
        $ship['NORTHSOUTH'] = $ship['NORTHSOUTH'] + ($waypoint['NORTHSOUTH'] * $numberTimes);
    }

    /**
     * Calculates new waypoint coordinates
     * @param $waypoint
     * @param $degrees
     * @return array
     */
    private function getNewWaypoint($waypoint, $degrees): array
    {
        $oldPos = $waypoint;
        switch ($degrees) {
            case 90:
                $waypoint['EASTWEST'] = $oldPos['NORTHSOUTH'];
                $waypoint['NORTHSOUTH'] = - $oldPos['EASTWEST'];
                return $waypoint;
            case 180:
                $waypoint['EASTWEST'] = - $oldPos['EASTWEST'];
                $waypoint['NORTHSOUTH'] = - $oldPos['NORTHSOUTH'];
                return $waypoint;
            case 270:
                $waypoint['EASTWEST'] = - $oldPos['NORTHSOUTH'];
                $waypoint['NORTHSOUTH'] =  $oldPos['EASTWEST'];
                return $waypoint;
        }
    }
}