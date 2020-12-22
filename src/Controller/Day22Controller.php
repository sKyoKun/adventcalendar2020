<?php

namespace App\Controller;

use App\Services\CalendarServices;
use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/day22")
 */
class Day22Controller extends AbstractController
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
     * @Route("/1/{file}", defaults={"file"="day22"})
     * @param string $file
     * @return JsonResponse
     */
    public function day22(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');
        $blankLine = array_search("", $inputs);
        $player1Cards = array_slice($inputs, 1, $blankLine-1);
        $player2Cards = array_slice($inputs, $blankLine+2);

        while(!empty($player1Cards) && !empty($player2Cards)) {
            $card1 = array_shift($player1Cards);
            $card2 = array_shift($player2Cards);
            $this->drawCardAndRetrieve($card1, $card2, $player1Cards, $player2Cards);
        }

        if(empty($player1Cards)) {
            $reversedDeck = array_reverse($player2Cards);
        } else {
            $reversedDeck = array_reverse($player1Cards);
        }

        $winnerDeckValue = $this->calculateDeckValue($reversedDeck);

        return new JsonResponse($winnerDeckValue, Response::HTTP_OK);
    }

    /**
     * @param $card1
     * @param $card2
     * @param $player1cards
     * @param $player2cards
     */
    private function drawCardAndRetrieve($card1, $card2, &$player1cards, &$player2cards)
    {
        if((int) $card1 < (int) $card2) {

            $player2cards[] = $card2;
            $player2cards[] = $card1;
        } else {
            $player1cards[] = $card1;
            $player1cards[] = $card2;
        }
    }

    /**
     * @param $deck
     *
     * @return int
     */
    private function calculateDeckValue($deck): int
    {
        $deckValue = 0;
        foreach ($deck as $pos => $card) {
            $deckValue += $card * ($pos + 1);
        }

        return $deckValue;
    }

    /**
     * @Route("/2/{file}", defaults={"file"="day22"})
     * @param string $file
     * @return JsonResponse
     */
    public function day22part2(string $file): JsonResponse
    {
        $inputs = $this->inputReader->getInput($file.'.txt');


        return new JsonResponse([], Response::HTTP_OK);
    }
}