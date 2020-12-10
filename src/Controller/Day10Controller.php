<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day10")
 */
class Day10Controller extends AbstractController
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
    public function day10()
    {
        $inputs = $this->inputReader->getInput('day10.txt');
        sort($inputs);
        $builtInJoltageAdapter = max($inputs) + 3;
        $currentJoltage = 0;
        $differentJolts = [
            1 => 0,
            2 => 0,
            3 => 1
        ];

        $differentJolts[$inputs[0]- $currentJoltage] += 1;

        for($i=0; $i<count($inputs)-1; $i++) {
            $difference = $inputs[$i+1] - $inputs[$i];
            $differentJolts[$difference] += 1;
        }

        dump($differentJolts[1]);
        dump($differentJolts[3]);
        dump($differentJolts[1] * $differentJolts[3]);

        die();
    }

    /**
    + @Route("/2")
     */
    public function day10Part2()
    {
        $inputs = $this->inputReader->getInput('day10.txt');
        sort($inputs);
        array_unshift($inputs, 0);

        // 0 ways is one way so our first value is set to 1
        // then all the ways to go to the key are set to 0
        $initializeValues = function($key) {
            if($key === 0) {
                return 1;
            } else {
                return 0;
            }
        };
        $ways = array_map($initializeValues, array_values($inputs));

        // we go through the array and for each input, we look for the 3 previous values
        // for each value that match, we add the ways for that value and our current ways
        // example : [ 0, 1, 2, 3]
        // there's 1 way for 0
        // 1 + 0 ways for 1
        // 1 + 1 + 0 ways for 2
        // 1 + 1 + 2 + 0 ways for 3 .... [0,1,2,3] / [0,1,3] / [0,2,3] / [0, 3]
        for ($i = 0; $i < count($inputs); $i++) {
            for ($j = $i - 3; $j < $i; $j++) {
                // add ways from 3 previous inputs
                if (isset($inputs[$j]) && $inputs[$i] <= $inputs[$j] + 3) {
                    $ways[$i] += $ways[$j];
                }
            }
        }

        dump(array_pop($ways));
        die();
    }

}