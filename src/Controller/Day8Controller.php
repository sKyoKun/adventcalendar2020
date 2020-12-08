<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day8")
 */
class Day8Controller extends AbstractController
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
    public function day8()
    {
        $inputs = $this->inputReader->getInput('day8.txt');

        $accumulator = 0;
        $passedInputs = [];
        $currentIndex = 0;

        while(false === in_array($currentIndex, $passedInputs)) {
            $passedInputs[] = $currentIndex;
            $instructionAndParam = explode(' ', $inputs[$currentIndex]);
            switch ($instructionAndParam[0]) {
                case "acc":
                    $accumulator += $instructionAndParam[1];
                    $currentIndex += 1;
                    break;
                case "jmp":
                    $currentIndex += $instructionAndParam[1];
                    break;
                case "nop":
                    $currentIndex += 1;
                    break;
            }
        }
        dump($accumulator);

        die();
    }

    private function processingArray($inputs, &$currentIndex, &$accumulator, &$lastJump, &$lastNop, &$iteration) {
        $passedInputs[] = $currentIndex;
        $instructionAndParam = explode(' ', $inputs[$currentIndex]);
        switch ($instructionAndParam[0]) {
            case "acc":
                $accumulator += $instructionAndParam[1];
                $currentIndex += 1;
                break;
            case "jmp":
                if($iteration == 1) { $lastJump = $currentIndex; }
                $currentIndex += $instructionAndParam[1];
                break;
            case "nop":
                if($iteration == 1) { $lastNop = $currentIndex; }
                $currentIndex += 1;
                break;
        }
        $iteration++;
    }

    /**
    + @Route("/2")
     */
    public function day8Part2()
    {
        $inputs = $this->inputReader->getInput('day8.txt');

        $accumulator = 0;
        $passedInputs = [];
        $currentIndex = 0;
        $lastJump = 0;
        $lastNop = 0;
        $iteration = 1;
        $usedJump = false;


        while($currentIndex < count($inputs)) {
            if (in_array($currentIndex, $passedInputs)) {
                dump("doublon");
                $passedInputs = [];
                $currentIndex = 0;
                if(!$usedJump) {
                    $usedJump = true;
                    $oldValue = explode(' ', $inputs[$lastJump]);
                    $inputs[$lastJump] = "nop ".$oldValue[1];
                } else {
                    $oldValue = explode(' ', $inputs[$lastNop]);
                    $inputs[$lastNop] = "jmp ".$oldValue[1];
                }

            }
            $this->processingArray($inputs, $currentIndex, $accumulator, $lastJump, $lastNop, $iteration);
            if ($currentIndex === count($inputs)) {
                dump($accumulator);
            }
        }
        //dump($accumulator);

        die();
    }

}