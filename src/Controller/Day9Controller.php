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
    }

}