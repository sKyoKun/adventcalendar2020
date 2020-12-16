<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day16")
 */
class Day16Controller extends AbstractController
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
     * @Route("/1/{file}", defaults={"file"="day16"})
     * @param string $file
     * @return JsonResponse
     */
    public function day16(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');


        return new JsonResponse(array_pop($spokenNumbers), Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day16"})
     * @param string $file
     * @return JsonResponse
     */
   /* public function day16part2(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        return new JsonResponse([], Response::HTTP_OK);
    }*/
}