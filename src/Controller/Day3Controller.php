<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day3")
 */
class Day3Controller extends AbstractController
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
    public function day3()
    {
        $inputs = $this->inputReader->getInput('day3.txt');

        $trees1 = $this->calendarServices->calculateTreesForTry($inputs, 1, 1, 1, 1);
        $trees2 = $this->calendarServices->calculateTreesForTry($inputs, 3, 1, 3, 1);
        $trees3 = $this->calendarServices->calculateTreesForTry($inputs, 5, 1, 5, 1);
        $trees4 = $this->calendarServices->calculateTreesForTry($inputs, 7, 1, 7, 1);
        $trees5 = $this->calendarServices->calculateTreesForTry($inputs, 1, 2, 1, 2);

        dump($trees1) ;
        dump($trees2) ;
        dump($trees3) ;
        dump($trees4) ;
        dump($trees5) ;
        dump($trees5* $trees4* $trees3*$trees2*$trees1);
        die();
    }
}