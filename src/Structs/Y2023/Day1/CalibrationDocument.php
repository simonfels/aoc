<?php

declare(strict_types=1);

namespace App\Structs\Y2023\Day1;

class CalibrationDocument {
    public function __construct(private readonly array $calibrationValues)
    {
    }

    private function CalibrationValues(): array {
        return array_map(
            fn($cv) => (new CalibrationValue($cv))->GetValue(), 
            $this->calibrationValues
        );
    }

    public function SumOfCalibrationValues(): int {
        return array_sum($this->CalibrationValues());
    }
}
