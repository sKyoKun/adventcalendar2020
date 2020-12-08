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

    /**
    + @Route("/2")
     */
    public function day8Part2()
    {
        $inputs = $originalInputs = $this->inputReader->getInput('day8.txt');

        $accumulator = 0;
        $executed = [];
        $currentIndex = 0;
        $hasChangedInput = false;
        $changedInputs = [];


        while($currentIndex < count($inputs)) {
            // if we find an index again, we looped, so we reset all the counters and $executed / $inputs arrays
            if (in_array($currentIndex, $executed)) {
                $accumulator = 0;
                $executed = [];
                $currentIndex = 0;
                $inputs = $originalInputs;
                $hasChangedInput = false;

            }
            // Lets get the current instruction
            $executed[] = $currentIndex;
            $instructionAndParam = explode(' ', $inputs[$currentIndex]);
            $instruction = $instructionAndParam[0];
            $number = $instructionAndParam[1];
            // If our instruction is a jmp or a nop, then we look if we already changed it and if we already changed one in this try
            if("acc" !== $instruction && false === in_array($currentIndex, $changedInputs) && false === $hasChangedInput) {
                // if it's a jump, we convert it as a nop and update our array, we put our current index in changedInputs Array
                // Same goes for a nop (converted to jmp)
                if($instruction === "jmp") {
                    $instruction = "nop";
                } else if($instruction === "nop") {
                    $instruction = "jmp";
                }
                $changedInputs[] = $currentIndex ;
                $inputs[$currentIndex] = $instruction.' '.$number;
                $hasChangedInput = true;
            }
            // then we process the instruction
            $this->processInstruction($instruction, $number, $currentIndex, $accumulator);

        }
        dump($accumulator);

        die();
    }


    private function processInstruction($instruction, $number, &$currentIndex, &$accumulator) {

        switch ($instruction) {
            case "acc":
                $accumulator += $number;
                $currentIndex += 1;
                break;
            case "jmp":
                $currentIndex += $number;
                break;
            case "nop":
                $currentIndex += 1;
                break;
        }
    }

}