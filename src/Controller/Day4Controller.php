<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day4")
 */
class Day4Controller extends AbstractController
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
    public function day4()
    {
        $validPassports = 0;
        $inputs = $this->inputReader->getInput('day4.txt');
        $passports = $this->calendarServices->parseInputsPassports($inputs);
        foreach ($passports as $passport) {
            if ($this->calendarServices->isPasswordValidWithRestrain($passport)) {
                $validPassports++;
            }
        }

        dump($validPassports);
        die();
    }

    /**
    + @Route("/2")
     */
    public function day4Part2()
    {
        $validPassports = 0;
        $inputs = $this->inputReader->getInput('day4.txt');
        $passports = $this->calendarServices->parseInputsPassports($inputs);
        foreach ($passports as $passport) {
            if ($this->calendarServices->isPasswordValidWithRestrain($passport)) {
                $validPassports++;
            }
        }

        dump($validPassports);
        die();
    }


}