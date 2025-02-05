<?php

declare(strict_types=1);

namespace App\Services\Y2023\Day1;

use App\Services\AbstractSolver;
use App\Structs\Y2023\Day1\CalibrationDocument;

class Solver extends AbstractSolver {
        
    public function firstStar(): int
    {
        return $this->oneliner();
    }

    public function secondStar(): mixed
    {
        return (new CalibrationDocument($this->getLines()))->SumOfCalibrationValues();
    }

    private function oneliner(): int {
        return array_sum(
            array_map(
                fn($a) => (int) ($a[0] . $a[strlen($a) - 1]), 
                array_map(
                    fn($i) =>preg_replace('/\D/', '', $i),
                    preg_split('/\s/', $this->getInput()
                    )
                )
            )
        );
    }
}
