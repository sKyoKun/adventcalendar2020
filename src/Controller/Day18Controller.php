<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/day18")
 */
class Day18Controller extends AbstractController
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
     * @Route("/1/{file}", defaults={"file"="day18"})
     * @param string $file
     * @return JsonResponse
     */
    public function day18(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        $linesValues = [];
        foreach($inputs as $lineNumber => $input) {
            $linesValues[$lineNumber] = 0;
            $chars = str_split($input);
            $chars = array_values(array_diff( $chars, [' '] )); // remove blank values
            $keysCloseParenthesis = array_keys($chars, ')');

            $count = 0;
            // While we still have some closed parenthesis
            while(!empty($keysCloseParenthesis)) {
                $keysOpenParenthesis  = array_keys($chars, '(');
                $previousOpenParenthesisKey = $keysCloseParenthesis[0];
                // first find the corresponding open parenthesis
                while(!\in_array($previousOpenParenthesisKey, $keysOpenParenthesis)) {
                    $previousOpenParenthesisKey--;
                }
                // let's calculate the inside of the parenthesis
                $length = $keysCloseParenthesis[0] - $previousOpenParenthesisKey - 1;
                $subArrayInParenthesis = array_values(array_slice($chars, $previousOpenParenthesisKey+1, $length));
                $currentValue = $this->calculate($subArrayInParenthesis, 0, count($subArrayInParenthesis)-1);
                $chars[$previousOpenParenthesisKey] = $currentValue;
                // then we remove the parenthesis and it's content
                unset($chars[$keysCloseParenthesis[0]]);
                $i = $previousOpenParenthesisKey+1;
                while($i < $keysCloseParenthesis[0]) {
                    unset($chars[$i]);
                    $i++;
                }

                // we rearrange keys
                $chars = array_values($chars);
                $keysCloseParenthesis = array_keys($chars, ')');
                $count++;
            }

            // and then we calculate the final value for this line
            $currentValue = $this->calculate($chars, 0, count($chars)-1);

            $linesValues[$lineNumber] = $currentValue;
        }

        return new JsonResponse(array_sum($linesValues), Response::HTTP_OK);
    }

    /**
     * @param $array
     * @param $start
     * @param $stop
     * @return int
     */
    private function calculate($array, $start, $stop) {
        $counter      = $start;
        $currentValue = 0;
        while($counter < $stop) {
            if($counter === $start) {
                $currentValue = (int)$array[$counter];
                $counter++;
            }
            else if ($array[$counter] === '+') {
                $currentValue = $currentValue + $array[$counter+1];
                $counter = $counter+2;
            }
            else if ($array[$counter] === '*') {
                $currentValue = $currentValue * $array[$counter+1];
                $counter = $counter+2;
            }
        }

        return $currentValue;
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day18"})
     * @param string $file
     * @return JsonResponse
     */
    public function day18part2(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');

        return new JsonResponse([], Response::HTTP_OK);
    }
}