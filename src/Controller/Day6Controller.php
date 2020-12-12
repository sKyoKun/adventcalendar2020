<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @param CalendarServices $calendarServices
     * @param InputReader $inputReader
     */
    public function __construct(CalendarServices $calendarServices, InputReader $inputReader)
    {
        $this->calendarServices = $calendarServices;
        $this->inputReader = $inputReader;
    }

    /**
     * @Route("/1/{file}", defaults={"file"="day6"})
     * @param string $file
     * @return JsonResponse
     */
    public function day6(string $file): JsonResponse
    {
        $nbAnswers = 0;
        $inputs = $this->inputReader->getInput($file.'.txt');

        $declarationPerGroup = $this->calendarServices->parseInputsDeclarationForms($inputs);
        foreach ($declarationPerGroup as $declaration) {
            $nbAnswers += $this->calendarServices->countDifferentAnswers($declaration);
        }

        return new JsonResponse($nbAnswers, Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day6"})
     * @param string $file
     * @return JsonResponse
     */
    public function day6Part2(string $file): JsonResponse
    {
        $nbAnswers = 0;
        $inputs = $this->inputReader->getInput($file.'.txt');

        $declarationPerGroup = $this->calendarServices->parseInputsDeclarationFormsToArrays($inputs);

        foreach ($declarationPerGroup as $peopleDeclarations) {
            $nbAnswers += $this->calendarServices->countQuestionAnsweredByEveryone($peopleDeclarations);
        }

        return new JsonResponse($nbAnswers, Response::HTTP_OK);
    }


}