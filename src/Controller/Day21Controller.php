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

        $ingredientAllergens = [];
        $foundIngredients = [];

        foreach ($inputs as $lineNb => $input) {
            $arrToParse = explode (' (contains ', $input);
            $allergensStr = rtrim($arrToParse[1], ')');
            $allergens = explode(', ', $allergensStr);
            $ingredients = explode(' ', $arrToParse[0]);

            foreach ($ingredients as $ingredient) {
                if(!array_key_exists($ingredient, $foundIngredients)) {
                    $foundIngredients[$ingredient] = 1;
                } else {
                    $foundIngredients[$ingredient]++;
                }
            }
            foreach ($allergens as $allergen) {
                $ingredientAllergens[$allergen][$lineNb] = $ingredients;
            }
        }

        // first let's find ingredients that are just one time in the list, they are not allergens
        foreach ($foundIngredients as $ingredient => $countFound) {
            if($countFound > 1) {
                continue;
            }
            $this->removeIngredient($ingredientAllergens, $ingredient);
        }

        // then let's match our allergens with ingredients
        $matchedAllergens = [];
        // while we didnt match all our allergens
        while(count($matchedAllergens) < count($ingredientAllergens)) {
            foreach ($ingredientAllergens as $allergen => $ingredientAllergen) {
                // we look for the intersection of our allergen and our ingredient (only ONE ingredient matches ONE allergen)
                $ingredient = array_intersect($ingredientAllergens[$allergen][array_key_first($ingredientAllergens[$allergen])], ...$ingredientAllergens[$allergen]);
                if (count($ingredient) === 1) {
                    // if we found an ingredient, we remove it from every other allergen
                    // and put it in our matched array
                    $strIngredient = array_shift($ingredient);
                    $matchedAllergens[$strIngredient] = $allergen;
                    $this->removeIngredient($ingredientAllergens, $strIngredient);
                }
            }
        }

        // finally let's found ingredients registered that are not in our matchedAllergen array
        $total = 0;
        foreach ($foundIngredients as $ingredient => $occurrence) {
            if(!array_key_exists($ingredient, $matchedAllergens)) {
                $total += $occurrence;
            }
        }

        return new JsonResponse($total, Response::HTTP_OK);
    }

    private function removeIngredient(&$ingredientAllergens, $ingredient) {
        foreach ($ingredientAllergens as $allergen => $arrRows) {
            foreach ($arrRows as $keyLine => $lines) {
                foreach ($lines as $keyIngredient => $currentIngredient) {
                    if ($currentIngredient === $ingredient) {
                        unset($ingredientAllergens[$allergen][$keyLine][$keyIngredient]);
                    }
                }
            }
        }
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