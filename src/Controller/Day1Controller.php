<?php

namespace App\Controller;

use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
* + @Route("/day1")
 */
class Day1Controller extends AbstractController
{
    private $inputReader;

    public function __construct(InputReader $inputReader)
    {
        $this->inputReader = $inputReader;
    }

    /**
    + @Route("/")
     */
    public function day1()
    {
        $numbers = $numbersInv = $this->inputReader->getInput('day1.txt');

        foreach ($numbers as $number) {
            $sub = 2020 - (int)$number;
            if (\in_array($sub, $numbers)) {
                echo $sub * $number; die();
            }
        }
    }

    /**
    + @Route("/2")
     */
    public function day1bis()
    {
        $numbers = $numbersInv = $this->inputReader->getInput('day1.txt');

        sort($numbers);
        rsort($numbersInv);
        foreach ($numbers as $number) {
            foreach ($numbersInv as $invNumber) {
                foreach ($numbers as $numberTwo)
                {
                    if ($number + $invNumber + $numberTwo === 2020) {
                        echo $number * $invNumber * $numberTwo; die();
                    }
                }
            }
        }
    }
}