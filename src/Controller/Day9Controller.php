<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day9")
 */
class Day9Controller extends AbstractController
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
     * @Route("/1/{file}/{preambleLength}", defaults={"file"="day9", "preambleLength"=25})
     * @param $file
     * @param $preambleLength
     * @return JsonResponse
     */
    public function day9(string $file, int $preambleLength)
    {
        $inputs = $this->inputReader->getInput($file.'.txt');
        $index = $preambleLength;

        while ($index < count($inputs)) {
            $found = false;
            $minIndex = $index - $preambleLength;
            for($i = $minIndex; $i < ($index-1); $i++) {
                for($j = $minIndex; $j < $index; $j++) {
                    if(((int)$inputs[$i] + (int)$inputs[$j]) === (int)$inputs[$index]) {
                        $found = true;
                    }
                }
            }
            if (false === $found) {
                return new JsonResponse($inputs[$index], Response::HTTP_OK);
            }
            ++$index;
        }

        return new JsonResponse(null, Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Route("/2/{file}/{numberToFind}", defaults={"file"="day9", "numberToFind"=23278925})
     * @param $file
     * @param $numberToFind
     * @return JsonResponse
     */
    public function day9Part2(string $file, int $numberToFind)
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        $numberToFindKey = array_search($numberToFind, $inputs);

        // We only get a subset of the original array 0 => key for selected value
        $subInputs = array_slice($inputs, 0, $numberToFindKey+1);

        // we go from our value, to the start of the array
        for ($i=($numberToFindKey-1); $i >=0; $i--) {
            $sum = 0;
            $counter = 0;
            while ($sum < $numberToFind) {
                $sum += $subInputs[$i-$counter];
                $counter++;
            }

            if($sum === $numberToFind) {
                // we found our subset of numbers, lets isolate these in a new array
                $finalArray=array_splice($subInputs,$i-$counter+1, $counter);
                // then we sort it to get the min and max value
                sort($finalArray);
                $min=array_shift($finalArray);
                $max=array_pop($finalArray);

                return new JsonResponse($min + $max, Response::HTTP_OK);
            }
            continue;
        }

        return new JsonResponse(null, Response::HTTP_NOT_ACCEPTABLE);
    }

}