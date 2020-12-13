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

        $buses = array_diff( $buses, ['x'] );

        $minArrivalTime = null;
        $bestBus = null;

        foreach ($buses as $bus) {
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

        $arrbuses = explode(',',$inputs[1]);

        $buses = [];
        foreach ($arrbuses as $departure => $bus) {
            if($bus !== 'x') {
                $buses[] = ['departure'=> $departure, 'id' => $bus];
            } else {
                $buses[] = ['departure'=> $departure, 'id' => -1 ];
            }
        }

        $found = false;
        $multiplier = 0;
        $increment = 1;

        while($found === false) {
            $buses[0]['departure'] = $buses[0]['id'] * $multiplier;
            $found = true;
            for ($i = 1; $i < count($buses); $i++) {
                if ($buses[$i]['id'] == -1) {
                    $buses[$i]['departure'] = $buses[$i-1]['departure'] + 1;
                    continue;
                }
                $newTime = 0;
                $j = 1;
                do {
                    $newTime = $buses[$i]['id'] * (floor($buses[0]['departure'] / $buses[$i]['id'] ) + $j++);
                } while (($newTime - $buses[$i-1]['departure']) <= 0);
                $buses[$i]['departure'] = $newTime;
                if (isset($buses[$i]['multiplier'])) {
                    $increment = $multiplier - $buses[$i]['multiplier'];
                }
                $buses[$i]['multiplier'] = $multiplier;
                if ($buses[$i]['departure'] != ($buses[$i-1]['departure'] + 1)) {
                    $found = false;
                    break;
                }
            }
            $multiplier += $increment;
        }

        return new JsonResponse($buses[0]['departure'], Response::HTTP_OK);
    }
}