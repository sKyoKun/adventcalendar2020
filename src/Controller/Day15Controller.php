<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day15")
 */
class Day15Controller extends AbstractController
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
     * @Route("/1/{file}", defaults={"file"="day15"})
     * @param string $file
     * @return JsonResponse
     */
    public function day15(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        // Let's get our numbers (explode is returning string, so we cast them)
        $spokenNumbers = explode(',', $inputs[0]);
        foreach ($spokenNumbers as $key => $number) {
            $spokenNumbers[$key] = (int) $number;
        }

        for ($i = count($spokenNumbers); $i < 2020; $i++) {
            $lastNumber = $spokenNumbers[$i-1];
            // if our number is only once in our array, let's add a 0
            if (array_count_values($spokenNumbers)[$lastNumber] === 1) {
                $spokenNumbers[] = 0;
            } else {
                // We have more than one occurrence
                // We reverse the array to have the last numbers first
                $reversedSpokenNumbers = array_reverse($spokenNumbers, true);
                $countToLastNumberOccurrence = null;
                $counter = 0;
                foreach ($reversedSpokenNumbers as $number) {
                    if($counter !== 0 && $number == $lastNumber) { // we dont want to count the first one
                        $countToLastNumberOccurrence = $counter;
                        break;
                    }
                    $counter++;
                }

                $spokenNumbers[] = $countToLastNumberOccurrence;
            }
        }

        return new JsonResponse(array_pop($spokenNumbers), Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day15"})
     * @param string $file
     * @return JsonResponse
     */
    public function day15part2(string $file): JsonResponse
    {
        ini_set("memory_limit", "-1");
        $inputs = $this->inputReader->getInput($file.'.txt');

        // Let's get our numbers (explode is returning string, so we cast them)
        $spokenNumbers = explode(',', $inputs[0]);
        // lastSeen array will take the last index for a number.
        $lastSeen = [];

        foreach ($spokenNumbers as $key => $number) {
            $number = (int) $number;
            $spokenNumbers[$key] = $number;
            $lastSeen[$number] = $key;
        }

        array_pop($lastSeen); // We need to process the last spoken number
        $start = count($spokenNumbers);

        for ($i = $start; $i < 30000000; $i++) {
            $lastPos = $i-1;
            $lastNumber = $spokenNumbers[$lastPos];

            // if our number has not been seen, then add a 0
            if (false === array_key_exists($lastNumber, $lastSeen)) {
                $spokenNumbers[] = 0;
            } else { // else our value is the difference between our lastPos and the lastSeen value
                $spokenNumbers[] = $lastPos - $lastSeen[$lastNumber];
            }
            $lastSeen[$lastNumber] = $lastPos;
        }

        return new JsonResponse(array_pop($spokenNumbers), Response::HTTP_OK);
    }
}