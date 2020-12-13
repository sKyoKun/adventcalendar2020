<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day13")
 */
class Day13Controller extends AbstractController
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
     * @Route("/1/{file}", defaults={"file"="day13"})
     * @param string $file
     * @return JsonResponse
     */
    public function day13(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        $timestamp = $inputs[0];
        $buses     = explode(',',$inputs[1]);

        $busesWithoutX = array_diff( $buses, ['x'] );

        $minArrivalTime = null;
        $bestBus = null;

        foreach ($busesWithoutX as $bus) {
            $arrivalTime = $bus - ($timestamp % $bus);
            if($minArrivalTime === null || ($minArrivalTime !== null && $arrivalTime < $minArrivalTime)) {
                $minArrivalTime = $arrivalTime;
                $bestBus        = $bus;
            }
        }

        return new JsonResponse($bestBus * $minArrivalTime, Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day13"})
     * @param string $file
     * @return JsonResponse
     */
    public function day13part2(string $file): JsonResponse
    {
        //set_time_limit(0);
        $inputs = $this->inputReader->getInput($file.'.txt');

        $buses = explode(',',$inputs[1]);
        $additionalTime = array_shift($buses);

        $found = 0;

        $busesWithoutX = [];
        foreach ($buses as $departure => $bus) {
            if($bus !== 'x') {
                // we should add 1 since we shifted the first bus (original 0)
                $busesWithoutX[$departure+1] = $bus;
            }
        }

        $max = (int)max($busesWithoutX);

        $timestamp = $additionalTime ;

        while($found < count($busesWithoutX)) {
            $found = 0;
            foreach ($busesWithoutX as $departure => $bus) {
                $currentTimestamp = $timestamp + $departure;

                $isArrivingTime = ((int)$currentTimestamp % (int)$bus === 0);
                $isDifferenceTheSameAsStart = ($currentTimestamp - $timestamp === (int)$departure);

                if($isArrivingTime && $isDifferenceTheSameAsStart) {
                    $found++;
                }
            }

            $timestamp += $additionalTime;
        }
        $timestamp -= $additionalTime;

        return new JsonResponse($timestamp, Response::HTTP_OK);
    }
}