<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/1/{file}", defaults={"file"="day4"})
     * @param string $file
     * @return JsonResponse
     */
    public function day4(string $file): JsonResponse
    {
        $validPassports = 0;
        $inputs = $this->inputReader->getInput($file.'.txt');
        $passports = $this->calendarServices->parseInputsPassports($inputs);
        foreach ($passports as $passport) {
            if ($this->calendarServices->isPasswordValidWithRestrain($passport)) {
                $validPassports++;
            }
        }

        return new JsonResponse($validPassports, Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day4"})
     * @param string $file
     * @return JsonResponse
     */
    public function day4Part2(string $file): JsonResponse
    {
        $validPassports = 0;
        $inputs = $this->inputReader->getInput($file.'.txt');
        $passports = $this->calendarServices->parseInputsPassports($inputs);
        foreach ($passports as $passport) {
            if ($this->calendarServices->isPasswordValidWithRestrain($passport)) {
                $validPassports++;
            }
        }

        return new JsonResponse($validPassports, Response::HTTP_OK);
    }


}