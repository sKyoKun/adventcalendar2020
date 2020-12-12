<?php

namespace App\Controller;

use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* + @Route("/day1")
 */
class Day1Controller extends AbstractController
{
    private $inputReader;

    /**
     * @param InputReader $inputReader
     */
    public function __construct(InputReader $inputReader)
    {
        $this->inputReader = $inputReader;
    }

    /**
     * @Route("/1/{file}", defaults={"file"="day1"})
     * @param string $file
     * @return JsonResponse
     */
    public function day1(string $file): JsonResponse
    {
        $numbers = $numbersInv = $this->inputReader->getInput($file.'.txt');

        foreach ($numbers as $number) {
            $sub = 2020 - (int)$number;
            if (\in_array($sub, $numbers)) {
                return new JsonResponse($sub * $number, Response::HTTP_OK);
            }
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day1"})
     * @param string $file
     * @return JsonResponse
     */
    public function day1bis(string $file): JsonResponse
    {
        $numbers = $numbersInv = $this->inputReader->getInput($file.'.txt');

        sort($numbers);
        rsort($numbersInv);
        foreach ($numbers as $number) {
            foreach ($numbersInv as $invNumber) {
                foreach ($numbers as $numberTwo)
                {
                    if ($number + $invNumber + $numberTwo === 2020) {
                        return new JsonResponse($number * $invNumber * $numberTwo, Response::HTTP_OK);
                    }
                }
            }
        }

        return new JsonResponse(null, Response::HTTP_NOT_ACCEPTABLE);
    }
}