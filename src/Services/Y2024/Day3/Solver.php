<?php

declare(strict_types=1);

namespace App\Services\Y2024\Day3;

use App\Services\AbstractSolver;

class Solver extends AbstractSolver {
    public function firstStar(): mixed
    {
        return $this->sumOfMultipliedMatches($this->getInput());
    }

    public function secondStar(): int
    {
        $dos = explode("do()", $this->getInput());

        return array_sum(
            array_map(
                fn($do) => $this->sumOfMultipliedMatches(explode("don't()", $do)[0]),
                $dos
            )
        );
    }

    private function sumOfMultipliedMatches(string $searchString): int
    {
        preg_match_all('/mul\((\d*),(\d*)\)/', $searchString, $matches);

        return array_sum(
            array_map(
                fn(int $m1, int $m2) => $m1 * $m2,
                $matches[1],
                $matches[2]
                )
        );
    }
}
