<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    + @Route("/")
     */
    public function day9()
    {
        //$inputs = $this->inputReader->getInput('day9test.txt');
        $inputs = $this->inputReader->getInput('day9.txt');
        $preambleLength = 25;
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
                dump($inputs[$index]);
                die();
            }
            ++$index;
        }

        die();
    }

    /**
    + @Route("/2")
     */
    public function day9Part2()
    {
        $inputs = $this->inputReader->getInput('day9.txt');

        $numberToFind = 23278925;
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


                dump($min + $max);
                die();
            }
            continue;
        }
    }

}