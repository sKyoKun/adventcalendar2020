<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day6")
 */
class Day6Controller extends AbstractController
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
    public function day6()
    {
        $nbAnswers = 0;
        $inputs = $this->inputReader->getInput('day6.txt');

        $declarationPerGroup = $this->calendarServices->parseInputsDeclarationForms($inputs);
        foreach ($declarationPerGroup as $declaration) {
            $nbAnswers += $this->calendarServices->countDifferentAnswers($declaration);
        }
        dump($nbAnswers);
        die();
    }

    /**
    + @Route("/2")
     */
    public function day6Part2()
    {
        $nbAnswers = 0;
        $inputs = $this->inputReader->getInput('day6.txt');

        $declarationPerGroup = $this->calendarServices->parseInputsDeclarationFormsToArrays($inputs);

        foreach ($declarationPerGroup as $peopleDeclarations) {
            $nbAnswers += $this->calendarServices->countQuestionAnsweredByEveryone($peopleDeclarations);
        }
        dump($nbAnswers);
        die();
    }


}