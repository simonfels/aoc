<?php

declare(strict_types=1);

namespace App\Services\Y2024\Day1;

use App\Services\AbstractSolver;

class Solver extends AbstractSolver {    
    private array $firstRow = [];
    private array $secondRow = [];

    public function __construct()
    {
        parent::__construct();

        foreach($this->getLines() as $row) {
            [$firstNumber, $secondNumber] = explode("   ", $row);
            $this->firstRow[] = $firstNumber;
            $this->secondRow[] = $secondNumber;
        }

        sort($this->firstRow);
        sort($this->secondRow);
    }

    public function firstStar(): int
    {
        return array_sum(
            array_map(
                fn(int $first, int $second) => \abs($first - $second),
                $this->firstRow,
                $this->secondRow
            )
        );
    }

    public function secondStar(): int
    {
        $secondRowCounts = array_count_values($this->secondRow);

        return array_sum(
            array_map(
                fn(int $first) => !empty($secondRowCounts[$first]) ? $first * $secondRowCounts[$first] : 0,
                $this->firstRow
            )
        );
    }
}
