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

    }

}