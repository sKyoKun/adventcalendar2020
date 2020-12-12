<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day5")
 */
class Day5Controller extends AbstractController
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
     * @Route("/1/{file}", defaults={"file"="day5"})
     * @param string $file
     * @return JsonResponse
     */
    public function day5(string $file): JsonResponse
    {
        $places = [];
        $inputs = $this->inputReader->getInput($file.'.txt');

        foreach ($inputs as $input) {
            $places[] = $this->calendarServices->parsePlace($input);
        }
        sort($places);
        $highestPlace = array_pop($places);

        return new JsonResponse($highestPlace, Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day5"})
     * @param string $file
     * @return JsonResponse
     */
    public function day5Part2(string $file)
    {
        $places = [];
        $inputs = $this->inputReader->getInput($file.'.txt');

        foreach ($inputs as $input) {
            $places[] = $this->calendarServices->parsePlace($input);
        }
        sort($places);

        foreach ($places as $place) {
            if(false === in_array($place+1, $places))
            {
                return new JsonResponse($place+1, Response::HTTP_OK);
            }
        }
    }


}