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
        $rangeNameAndValues = [];
        $rangeLines = array_slice($inputs, 0, $blankLines[0]);
        $rangeValues = $this->getRangeValues($rangeLines);

        // We want $rangeNameAndValues['name'] => [acceptedvalues]
        foreach ($rangeLines as $line) {
            $rangeKeyValues = explode(': ', $line);
            $ranges = explode(' or ', $rangeKeyValues[1]);
            foreach($ranges as $range) {
                $bounds = explode('-', $range);
                $rangeNameAndValues[$rangeKeyValues[0]][] = range((int)$bounds[0], (int)$bounds[1]);
            }
        }

        $ourTicket     = array_slice($inputs, $blankLines[0]+2, 1);
        $othersTickets = array_slice($inputs, $blankLines[1]+2);

        // remove invalid tickets
        foreach ($othersTickets as $key => $otherTicket) {
            if ( !empty($this->invalidValueInTicket($rangeValues, $otherTicket))) {
                unset($othersTickets[$key]);
            }
        }

        // Get what ranges we find with each position
        $posFoundInRanges = [];
        foreach ($othersTickets as $validTicket) {
            $values = explode(',', $validTicket);
            foreach ($values as $pos => $value) {
                foreach ($rangeNameAndValues as $rangeName => $ranges) {
                    if(in_array($value, $ranges[0]) || in_array($value, $ranges[1])) {
                        $posFoundInRanges[$pos][] = $rangeName;
                    }
                }
            }
        }

        // We count occurrences of the same range name for each position and remove the irrelevant ones
        $countForNames = [];
        foreach ($posFoundInRanges as $pos => $rangeNames) {
            $countForNames[$pos] = array_count_values($rangeNames);
            $max = max($countForNames[$pos]);
            foreach ($countForNames[$pos] as $range => $count) {
                if($count !== $max) {
                    unset($countForNames[$pos][$range]);
                }
            }
        }

        // Then while we dont have the same number between the positions number and the range
        // We look if a position has only one range, if so, we delete this range from the array for others positions
        $posForRange = [];
        while(count($posForRange) !== count($rangeNameAndValues)) {
            foreach ($countForNames as $pos => $ranges) {
                if(count($ranges) === 1) {
                    $uniqueRange = array_key_first($ranges);
                    $posForRange[$pos] = $uniqueRange;
                    foreach ($countForNames as $posToDelete => $rangeToDelete) {
                        if(array_key_exists($uniqueRange, $rangeToDelete)) {
                            unset($countForNames[$posToDelete][$uniqueRange]);
                        }
                    }
                }
            }
        }

        // Now that we have a pos = range, lets get our tickets values
        $ourTicketArray = explode(',', $ourTicket[0]);
        $departureValue1 = $ourTicketArray[(array_search("departure location", $posForRange))];
        $departureValue2 = $ourTicketArray[(array_search("departure station", $posForRange))];
        $departureValue3 = $ourTicketArray[(array_search("departure platform", $posForRange))];
        $departureValue4 = $ourTicketArray[(array_search("departure track", $posForRange))];
        $departureValue5 = $ourTicketArray[(array_search("departure date", $posForRange))];
        $departureValue6 = $ourTicketArray[(array_search("departure time", $posForRange))];

        $total = $departureValue1 * $departureValue2 * $departureValue3 * $departureValue4 * $departureValue5 * $departureValue6;
        return new JsonResponse($total, Response::HTTP_OK);
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