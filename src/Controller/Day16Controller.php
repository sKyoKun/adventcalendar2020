<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * STORY OF A TICKET
 *
 * .--------------------------------------------------------.
 * | ????: 101    ?????: 102   ??????????: 103     ???: 104 |
 * |                                                        |
 * | ??: 301  ??: 302             ???????: 303      ??????? |
 * | ??: 401  ??: 402           ???? ????: 403    ????????? |
 * '--------------------------------------------------------'
 *
 * @Route("/day16")
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
        $blankLines = [];
        foreach ($inputs as $key => $input) {
            if($input === "") {
                $blankLines[] = $key;
            }
        }
        $rangeLines = array_slice($inputs, 0, $blankLines[0]);
        $rangeValues = $this->getRangeValues($rangeLines);
        $ourTicket = array_slice($inputs, $blankLines[0]+2, 1);
        $othersTickets = array_slice($inputs, $blankLines[1]+2);

        $invalidValues = [];

        foreach ($othersTickets as $oTicket) {
            $notInRange = $this->invalidValueInTicket($rangeValues, $oTicket);
            $invalidValues = array_merge($invalidValues, $notInRange);
        }

        return new JsonResponse(array_sum($invalidValues), Response::HTTP_OK);
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day16"})
     * @param string $file
     * @return JsonResponse
     */
    public function day16part2(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');
        $blankLines = [];
        foreach ($inputs as $key => $input) {
            if($input === "") {
                $blankLines[] = $key;
            }
        }
        $rangeLines = array_slice($inputs, 0, $blankLines[0]);
        $rangeValues = $this->getRangeValues($rangeLines);
        $ourTicket = array_slice($inputs, $blankLines[0]+2, 1);
        $othersTickets = array_slice($inputs, $blankLines[1]+2);

        $invalidValues = [];

        foreach ($othersTickets as $key => $oTicket) {
            $notInRange = $this->invalidValueInTicket($rangeValues, $oTicket);
            if (!empty($notInRange)) {
                $invalidValues = array_merge($invalidValues, $notInRange);
                unset($othersTickets[$key]);
            }

        }

        return new JsonResponse(array_sum($invalidValues), Response::HTTP_OK);
    }

    /**
     * @param $inputs
     * @return array
     */
    private function getRangeValues($inputs) {
        foreach ($inputs as $input) {
            if(strstr($input, 'or')) {
                $stringWithoutType = explode(': ', $input)[1];
                $ranges = explode(' or ', $stringWithoutType);
                $rangeValues[] = $ranges[0];
                $rangeValues[] = $ranges[1];
            }
        }

        return $rangeValues;
    }

    /**
     * @param $ranges
     * @param $ticket
     * @return array
     */
    private function invalidValueInTicket($ranges, $ticket) {
        $ticketValues = explode(',', $ticket);
        $notInRange = [];
        sort($ticketValues);
        foreach ($ticketValues as $value) {
            $notInRangeCount = 0;

            foreach ($ranges as $range) {
                $bounds = explode('-', $range);
                if(false === $this->isInBound($value, (int)$bounds[0], (int)$bounds[1])) {
                    $notInRangeCount++;
                }
            }

            if($notInRangeCount === count($ranges)) {
                $notInRange[] = (int)$value;
                unset()
            }
        }

        return $notInRange;
    }

    private function isInBound($value, $minBound, $maxBound) {
        if ($value < $minBound || $value  > $maxBound) {
            return false;
        }
        return true;
    }
}