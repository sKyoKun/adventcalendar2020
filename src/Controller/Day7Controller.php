<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day7")
 */
class Day7Controller extends AbstractController
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
    public function day7()
    {
        $inputs = $this->inputReader->getInput('day7.txt');

        $bagColors = [];
        $this->calendarServices->getBagsForColor('shiny gold', $inputs, $bagColors);

        dump(count(array_unique($bagColors)));
        die();
    }

    /**
    + @Route("/2")
     */
    public function day7Part2()
    {
        $inputs = $this->inputReader->getInput('day7.txt');

        $colorsRules = [];
        $this->calendarServices->getBagRulesForColor('shiny gold', $inputs, $colorsRules);
        $bagCount = $this->calendarServices->getBagCountFromRulesForColor('shiny gold', $colorsRules);


        dump($colorsRules);
        dump($bagCount-1); // we dont want to count the first gold bag
        die();
    }


}