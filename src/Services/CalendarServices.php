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

    public function isPasswordValidWithRestrain(array $passport){
        if (false === $this->isPassportValid($passport)) {
            return false;
        }

        if ((int)$passport['byr'] < 1920 && (int)$passport['byr'] > 2002) {
            return false;
        }
        if ((int)$passport['iyr'] < 2010 && (int)$passport['byr'] > 2020) {
            return false;
        }
        if ((int)$passport['eyr'] < 2020 && (int)$passport['eyr'] > 2030) {
            return false;
        }
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