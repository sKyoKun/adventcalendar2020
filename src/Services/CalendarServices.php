<?php


namespace App\Services;

class CalendarServices
{
    public function calculateTreesForTry($inputs, $startX, $startY, $plusX, $plusY) {
        $trees = 0;
        $squares = 0;

        $currentX = $startX;
        $currentY = $startY;

        while(isset($inputs[$currentY])) {
            $typology = $inputs[$currentY][$currentX % strlen($inputs[$currentY])];
            if ("#" === $typology) {
                $trees++;
            } else {
                $squares++;
            }

            $currentX = $currentX + $plusX;
            $currentY = $currentY + $plusY;
        }

        return $trees;
    }

    public function parseInputsPassports(array $inputs)
    {
        $passports = [];
        $lastBlankLineValue = 0;
        foreach ($inputs as $lineNumber => $input) {
            $previousPassportString = '';
            if($input == '') {
                for($i = $lastBlankLineValue; $i < $lineNumber; $i++) {
                    $previousPassportString .= ' '.$inputs[$i];
                }
                $passports[] = $this->parsePassportLine($previousPassportString);
                $lastBlankLineValue = $lineNumber+1;
            }
        }

        // get the last passport as we can't go in the if = ""
        $lastPassportString = '';
        for($i = $lastBlankLineValue; $i < count($inputs); $i++) {
            $lastPassportString .= ' '.$inputs[$i];
        }
        $passports[] = $this->parsePassportLine($lastPassportString);

        return $passports;
    }

    // DAY 4

    public function isPassportValid(array $passport)
    {
        $validKeys = ['byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid', 'cid'];

        if (count($passport) === count($validKeys)) {
            $arrayDiff = array_diff($validKeys, array_keys($passport));
            if (empty($arrayDiff)) {
                return true;
            }
        }

        // si j'ai pile 1 de moins et que j'ai pas le cid
        if ((count($passport) === (count($validKeys) -1)) && (false === array_key_exists('cid', $passport))) {
            // si j'ai aucune différence sur mon tableau de clé
            $arrayDiff = array_diff($validKeys, array_keys($passport));

            if (count($arrayDiff) == 1 && 'cid' == $arrayDiff[7]) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function isPasswordValidWithRestrain(array $passport): bool{
        if (false === $this->isPassportValid($passport)) {
            return false;
        }

        if ((int)$passport['byr'] < 1920 || (int)$passport['byr'] > 2002) {
            return false;
        }
        if ((int)$passport['iyr'] < 2010 || (int)$passport['iyr'] > 2020) {
            return false;
        }
        if ((int)$passport['eyr'] < 2020 || (int)$passport['eyr'] > 2030) {
            return false;
        }
        $heightCm = strstr($passport['hgt'], 'cm', true);
        $heightIn = strstr($passport['hgt'], 'in', true);
        if (!$heightCm && !$heightIn) {
            return false;
        }

        if ($heightCm && ((int)$heightCm < 150 || (int)$heightCm > 193)) {
            return false;
        }

        if ($heightIn && ((int)$heightIn < 59 || (int)$heightIn > 76)) {
            return false;
        }

        if (false === strpos($passport['hcl'], '#')) {
            return false;
        }

        if (strlen($passport['hcl']) !== 7) {
            return false;
        }

        if (false === ctype_xdigit(ltrim($passport['hcl'], '#'))) {
            return false;
        }

        if (false === \in_array($passport['ecl'], ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth'])) {
            return false;
        }

        if(strlen($passport['pid']) !== 9) {
            return false;
        }

        if(false === is_numeric($passport['pid'])) {
            return false;
        }

        return true;
    }

    // DAY 5

    public function parsePlace(string $code) {
        $rows = substr($code, 0, 7);
        $seats = substr($code, -3);

        $myRow = $this->calculateRowAndPlace($rows, 0, 127, 'F', 'B');
        $mySeat = $this->calculateRowAndPlace($seats, 0, 7, 'L', 'R');

        $seatId = ($myRow * 8) + $mySeat;

        return $seatId;
    }

    // DAY 6
    public function parseInputsDeclarationForms(array $inputs)
    {
        $answersPerGroup = [];
        $lastBlankLineValue = 0;
        foreach ($inputs as $lineNumber => $input) {
            $previousAnswer = '';
            if($input == '') {
                for($i = $lastBlankLineValue; $i < $lineNumber; $i++) {
                    $previousAnswer .= $inputs[$i];
                }
                $answersPerGroup[] = $previousAnswer;
                $lastBlankLineValue = $lineNumber+1;
            }
        }

        // get the last passport as we can't go in the if = ""
        $lastAnswer = '';
        for($i = $lastBlankLineValue; $i < count($inputs); $i++) {
            $lastAnswer .= $inputs[$i];
        }
        $answersPerGroup[] = $lastAnswer;

        return $answersPerGroup;
    }

    public function parseInputsDeclarationFormsToArrays(array $inputs)
    {
        $answersPerGroup = [];
        $lastBlankLineValue = 0;
        foreach ($inputs as $lineNumber => $input) {
            $previousAnswer = [];
            if($input == '') {
                for($i = $lastBlankLineValue; $i < $lineNumber; $i++) {
                    $previousAnswer[] = mb_str_split($inputs[$i]);
                }
                $answersPerGroup[] = $previousAnswer;
                $lastBlankLineValue = $lineNumber+1;
            }
        }

        // get the last passport as we can't go in the if = ""
        $lastAnswer = [];
        for($i = $lastBlankLineValue; $i < count($inputs); $i++) {
            $lastAnswer[] = mb_str_split($inputs[$i]);
        }
        $answersPerGroup[] = $lastAnswer;

        return $answersPerGroup;
    }

    public function countDifferentAnswers(string $answers): int {
        $answersArray = mb_str_split($answers);
        $uniqueAnswers = array_unique($answersArray);

        return count($uniqueAnswers);
    }

    public function countQuestionAnsweredByEveryone(array $peopleDeclaration): int {
        $commonAnswers = array_intersect($peopleDeclaration[0], ...$peopleDeclaration);

        return count($commonAnswers);
    }

    // DAY 7
    public function getBagsForColor(string $color, array $inputs, &$alreadyFoundColors)
    {
        foreach ($inputs as $input) {
            $contained = strstr($input, 'contain');
            if (strstr($contained, $color)) {
                $currentColor = strstr($input, ' bags', true);
                $alreadyFoundColors[] = $currentColor;
                $this->getBagsForColor($currentColor, $inputs, $alreadyFoundColors);
            }

            continue;
        }
    }

    /**
     * Retrieve all colors needed for shiny gold
     * ["color" => number bags]
     * @param string $color
     * @param array $inputs
     * @param $colorsRules
     */
    public function getBagRulesForColor(string $color, array $inputs, &$colorsRules)
    {
        foreach ($inputs as $input) {
            $currentColor = strstr($input, ' bags', true);
            if ($color === $currentColor) {
                $containedString = substr($input,strpos($input, 'contain ')+8);
                $containedColors = explode(', ', $containedString);
                foreach ($containedColors as $containedColor) {
                    $currentColorAndNumber = strstr($containedColor, ' bag', true);
                    if($currentColorAndNumber !== "no other") {
                        $number = $currentColorAndNumber[0];
                        $color = substr($currentColorAndNumber, 2);
                        $colorsRules[$currentColor][$color] = $number;
                        $this->getBagRulesForColor($color, $inputs, $colorsRules);
                    }
                    else {
                        $colorsRules[$currentColor] = "no other";
                    }
                }
            }

            continue;
        }
    }

    /**
     * From the colors found with above function, get the total bag count
     * If a bag color is processed, we should add 1 for the bag itself and number * color
     * @param string $color
     * @param array $colorsRules
     *
     * @return float|int
     */
    public function getBagCountFromRulesForColor(string $color, array $colorsRules) {
        $total = 0;
        foreach ($colorsRules as $key => $rules) {
            if ($key === $color) {
                $total+= 1; // every time I pass a bag color i should count the bag
                if ($rules == "no other") {
                    return 1;
                } else {
                    foreach($rules as $currentColor => $number) {
                        $total += $number * $this->getBagCountFromRulesForColor($currentColor, $colorsRules);
                    }
                    return $total;
                }
            }
        }
    }

    private function calculateRowAndPlace($rows, $minRange, $maxRange, $lowerHalf, $upperHalf) {
        $currentMaxRange = $maxRange;
        $currentMinRange = $minRange;
        $rowDirection ='';
        while (strlen($rows) > 0)
        {
            $rowDirection = $rows[0];

            if ($rowDirection == $lowerHalf) {
                $currentMaxRange = $currentMaxRange - (int)ceil(($currentMaxRange - $currentMinRange) / 2);
            } else if ($rowDirection == $upperHalf) {
                $currentMinRange = $currentMinRange + (int)ceil(($currentMaxRange - $currentMinRange) / 2);
            }
            $rows = substr($rows, 1);
        }

        if ($rowDirection === $lowerHalf) {
            return $currentMinRange;
        }
        return $currentMaxRange;
    }

    private function parsePassportLine(string $line) {
        $previousPassportContent = explode(' ', $line);
        foreach ($previousPassportContent as $keyValuePairs) {
            if('' !== $keyValuePairs) {
                $explodedPairs = explode(':', $keyValuePairs);
                $previousPassport[$explodedPairs[0]] = $explodedPairs[1];
            }
        }
        return $previousPassport;
    }
}