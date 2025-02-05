<?php

declare(strict_types=1);

namespace App\Structs\Y2023\Day1;

class CalibrationValue {
    const MATCH_WORDS = ['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9];

    function __construct(private readonly string $obfuscatedValues) {
    }

    public function GetValue(): int {        
        $decryptedValues = $this->RemoveLetters();

        return (int) ($decryptedValues[0] . $decryptedValues[strlen($decryptedValues) - 1]);
    }

    private function RemoveLetters(): string {
        return preg_replace('/\D/', '', $this->ReplaceWordWithNumbers($this->obfuscatedValues));
    }

    private function ReplaceWordWithNumbers(string $input): string {
        $preg_match = '/(' . implode('|', array_keys($this::MATCH_WORDS)) . ')/';

        return preg_replace_callback($preg_match, fn($matches) => $this::MATCH_WORDS[$matches[0]], $input);
    }
}
