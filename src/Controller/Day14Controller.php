<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day14")
 */
class Day14Controller extends AbstractController
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
     * @Route("/1/{file}", defaults={"file"="day14"})
     * @param string $file
     * @return JsonResponse
     */
    public function day14(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        $inputMask = array_shift($inputs);
        $mask = ltrim($inputMask, 'mask = ');

        $memory = [];

        foreach ($inputs as $input) {
            if(strstr($input, 'mask')) {
                $mask = ltrim($input, 'mask = ');
            } else {
                $memoryAndValue = explode("=", $input);
                preg_match("/[0-9]+/", $memoryAndValue[0],$matches);
                $memoryAddress = (int) $matches[0];
                $decimalValue = (int)trim($memoryAndValue[1]);
                $binaryValue = base_convert($decimalValue, 10, 2);
                while(strlen($binaryValue) < 36) {
                    $binaryValue = '0'.$binaryValue;
                }
                $binaryValue = $this->applyMask($binaryValue, $mask);
                $newDecimalValue =  base_convert($binaryValue, 2, 10);
                $memory[$memoryAddress] = $newDecimalValue;
            }
        }

        return new JsonResponse(array_sum($memory), Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day14"})
     * @param string $file
     * @return JsonResponse
     */
    public function day14part2(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        return new JsonResponse([], Response::HTTP_OK);
    }

    /**
     * Changes 1 and 0 of mask in string in parameter
     * @param $value
     * @param $mask
     *
     * @return mixed
     */
    private function applyMask($value, $mask)
    {
        for($i=0; $i < strlen($mask); $i++) {
            if($mask[$i] === 'X') {
                continue;
            }
            $value[$i] = $mask[$i];
        }

        return $value;
    }
}