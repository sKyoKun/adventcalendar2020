<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day5")
 */
class Day5Controller extends AbstractController
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
    public function day5()
    {
        $places = [];
        $inputs = $this->inputReader->getInput('day5.txt');

        foreach ($inputs as $input) {
            $places[] = $this->calendarServices->parsePlace($input);
        }
        sort($places);
        $highestPlace = array_pop($places);

        dump($highestPlace);
        die();
    }

    /**
    + @Route("/2")
     */
    public function day5Part2()
    {
        $places = [];
        $inputs = $this->inputReader->getInput('day5.txt');

        foreach ($inputs as $input) {
            $places[] = $this->calendarServices->parsePlace($input);
        }
        sort($places);

        foreach ($places as $place) {
            if(false === in_array($place+1, $places))
            {
                dump($place+1);die();
            }
        }
    }


}