<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/1/{file}", defaults={"file"="day3"})
     * @param string $file
     * @return JsonResponse
     */
    public function day3Part1(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        $trees  = $this->calendarServices->calculateTreesForTry($inputs, 3, 1, 3, 1);

        return new JsonResponse($trees, Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day3"})
     * @param string $file
     * @return JsonResponse
     */
    public function day3Part2(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        $trees1 = $this->calendarServices->calculateTreesForTry($inputs, 1, 1, 1, 1);
        $trees2 = $this->calendarServices->calculateTreesForTry($inputs, 3, 1, 3, 1);
        $trees3 = $this->calendarServices->calculateTreesForTry($inputs, 5, 1, 5, 1);
        $trees4 = $this->calendarServices->calculateTreesForTry($inputs, 7, 1, 7, 1);
        $trees5 = $this->calendarServices->calculateTreesForTry($inputs, 1, 2, 1, 2);

        return new JsonResponse($trees5* $trees4* $trees3*$trees2*$trees1, Response::HTTP_OK);
    }
}