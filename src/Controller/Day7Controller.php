<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/1/{file}", defaults={"file"="day7"})
     * @param string $file
     * @return JsonResponse
     */
    public function day7(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        $bagColors = [];
        $this->calendarServices->getBagsForColor('shiny gold', $inputs, $bagColors);

        return new JsonResponse(count(array_unique($bagColors)), Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day7"})
     * @param string $file
     * @return JsonResponse
     */
    public function day7Part2(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        $colorsRules = [];
        $this->calendarServices->getBagRulesForColor('shiny gold', $inputs, $colorsRules);
        $bagCount = $this->calendarServices->getBagCountFromRulesForColor('shiny gold', $colorsRules);

        return new JsonResponse($bagCount-1, Response::HTTP_OK);
    }


}