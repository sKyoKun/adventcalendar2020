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
                $binaryMemoryAddress = base_convert($memoryAddress, 10, 2);
                while(strlen($binaryMemoryAddress) < 36) {
                    $binaryMemoryAddress = '0'.$binaryMemoryAddress;
                }
                $addresses = $this->getAddressesFromMask($binaryMemoryAddress, $mask);

                foreach ($addresses as $address) {
                    $memory[$address] = $decimalValue;
                }
            }
        }

        return new JsonResponse(array_sum($memory), Response::HTTP_OK);
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

    /**
     * Gets possible addresses from binary address and mask.
     * @param $binaryAddress
     * @param $mask
     *
     * @return array
     */
    private function getAddressesFromMask($binaryAddress, $mask): array
    {
        // first : get the binary address combined with the mask
        // If 0, the address does not change, if 1 or X, print them
        for($i=0; $i < strlen($mask); $i++) {
            if($mask[$i] === '0') {
                continue;
            }
            $binaryAddress[$i] = $mask[$i];
        }

        $possibleCombinations[] = '';
        for($i = 0; $i < strlen($binaryAddress); $i++)
        {
            // if we are on a X, then we should retrieve and remove the current possible value from our array
            // append to it a 0 or a 1 and then add these possibility to our array
            if($binaryAddress[$i] == 'X') {
                $currentCombinationNumber = count($possibleCombinations);
                for($j = 0; $j < $currentCombinationNumber; $j++) {
                    $currentValue = $possibleCombinations[$j];
                    unset($possibleCombinations[$j]);
                    $possibleCombinations[] = $currentValue.'0';
                    $possibleCombinations[] = $currentValue.'1';
                }
            } else { // Else, just append the value to every possibilities
                $currentCombinationNumber = count($possibleCombinations);
                for($v = 0; $v < $currentCombinationNumber; $v++) {
                    $possibleCombinations[$v] = $possibleCombinations[$v].$binaryAddress[$i];
                }
            }
            // reorganize the keys as unset is not moving the keys.
            $possibleCombinations = array_values($possibleCombinations);
        }

        $addresses = [];
        foreach ($possibleCombinations as $combination) {
            $addresses[] = base_convert($combination, 2, 10);
        }

        return $addresses;
    }
}