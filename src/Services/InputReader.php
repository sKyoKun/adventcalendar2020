<?php


namespace App\Services;

class InputReader
{
    private $fileDir;

    public function __construct($fileDir)
    {
        $this->fileDir = $fileDir;
    }

    /**
     * @param string $file
     * @return array
     */
    public function getInput(string $file): array
    {
        $inputs = [];
        $content = fopen($this->fileDir . $file, 'r');

        while (($line = fgets($content)) !== false) {
            $lineWithoutSpaces = trim($line);
            $inputs[] = $lineWithoutSpaces;
        }

        fclose($content);

        return $inputs;
    }

}