<?php


namespace App\Services;

class InputReader
{
    /**
     * @param string $file
     * @return array
     */
    public function getInput(string $file): array
    {
        $inputs = [];
        $content = fopen('./Files/'.$file, 'r');

        while (($line = fgets($content)) !== false) {
            $lineWithoutSpaces = trim($line);
            $inputs[] = $lineWithoutSpaces;
        }

        fclose($content);

        return $inputs;
    }

}