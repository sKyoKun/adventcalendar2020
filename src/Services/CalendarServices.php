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
}