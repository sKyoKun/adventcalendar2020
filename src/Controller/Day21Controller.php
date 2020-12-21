<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/day21")
 */
class Day21Controller extends AbstractController
{
    /** @var InputReader */
    private $inputReader;

    /**
     * @param InputReader $inputReader
     */
    public function __construct(InputReader $inputReader)
    {
        $this->inputReader = $inputReader;
    }

    /**
     * @Route("/1/{file}", defaults={"file"="day21"})
     * @param string $file
     * @return JsonResponse
     */
    public function day21(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        return new JsonResponse([], Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day21"})
     * @param string $file
     * @return JsonResponse
     */
    public function day21part2(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        return new JsonResponse([], Response::HTTP_OK);
    }
}