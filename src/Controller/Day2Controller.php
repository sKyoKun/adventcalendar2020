<?php


namespace App\Controller;

use App\Services\InputReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
+ @Route("/day2")
 */
class Day2Controller extends AbstractController
{
    private $inputReader;

    public function __construct(InputReader $inputReader)
    {
        $this->inputReader = $inputReader;
    }

    /**
    + @Route("/")
     */
    public function day2()
    {
        $passwordsAndRules = $this->inputReader->getInput('day2.txt');

        $numberMatches = 0;
        foreach ($passwordsAndRules as $passwordAndRule) {
            $ruleAndPass = explode(':', $passwordAndRule);
            $rules = explode(' ', $ruleAndPass[0]);
            $numberTime = $rules[0];
            $arrNumberTime = explode('-', $numberTime);
            $letter = $rules[1];
            $password = trim($ruleAndPass[1]);

            // regex : Any other caracters than the letter + the letter between {x,y} times followed by other caracters than the letter
            $regex = '/^([^'.$letter.']*'.$letter.'){'.$arrNumberTime[0].','.$arrNumberTime[1].'}[^'.$letter.']*$/';

            if (1 === preg_match ( $regex , $password)) {
                echo $password.' matches '.$regex. '<br/>';
                $numberMatches++;
            }

        }
        echo $numberMatches;
        die();
    }

    /**
    + @Route("/2")
     */
    public function day2Bis()
    {
        $passwordsAndRules = $this->inputReader->getInput('day2.txt');

        $numberMatches = 0;
        foreach ($passwordsAndRules as $passwordAndRule) {
            $ruleAndPass = explode(':', $passwordAndRule);
            $rules = explode(' ', $ruleAndPass[0]);

            $letter = $rules[1];
            $password = trim($ruleAndPass[1]);

            $placeNumbers = $rules[0];
            $arrPlaces = explode('-', $placeNumbers);
            $firstChar = (int)$arrPlaces[0] - 1;
            $secChar = (int)$arrPlaces[1] - 1;

            // if the char is present 2 times, that does not work, it should be at ONE of the places
            if (($letter === $password[$firstChar] || $letter === $password[$secChar]) && ($password[$firstChar] !== $password[$secChar])) {
                $numberMatches++;
            }
        }
        echo $numberMatches;
        die();
    }
}