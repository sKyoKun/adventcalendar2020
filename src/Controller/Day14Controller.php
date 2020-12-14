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
        $mask0 = [];
        $mask1 = [];
        $this->updateMask($inputMask, $mask0, $mask1);

        $memory = [];

        foreach ($inputs as $input) {
            if(strstr('mask', $input)) {
                $this->updateMask($input, $mask0, $mask1);
            } else {
                $memoryAndValue = explode("=", $input);
                preg_match("/[0-9]+/", $memoryAndValue[0],$matches);
                $memoryAddress = (int) $matches[0];
                $decimalValue = (int)trim($memoryAndValue[1]);
                $binaryValue = sprintf( "%036d", decbin( $decimalValue));
                foreach ($mask0 as $posOne) {
                    $binaryValue[$posOne] = 1;
                }
                foreach ($mask1 as $posZero) {
                    $binaryValue[$posZero] = 0;
                }
                $newDecimalValue = bindec($binaryValue);
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

    private function strposAll($needle, $haystack) {
        $offset = 0;
        $allPos = array();
        while (($pos = mb_strpos($haystack, $needle, $offset)) !== FALSE) {
            $offset   = $pos + 1;
            $allPos[] = $pos;
        }
        return $allPos;
    }

    private function updateMask(&$maskString, &$mask0, &$mask1) {
        $mask  = ltrim($maskString, 'mask = ');
        $mask0 = $this->strposAll('1', $mask);
        $mask1 = $this->strposAll('0', $mask);
    }
}