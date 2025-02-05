<?php

declare(strict_types=1);

namespace App\Services\Y2024\Day2;

use App\Services\AbstractSolver;
use App\Structs\Y2024\Day2\SafeChecker;

class Solver extends AbstractSolver {
    public function firstStar(): int
    {
        return (new SafeChecker($this->getLines()))->checkReports();
    }

    public function secondStar(): int
    {
        return (new SafeChecker($this->getLines()))->checkVariations();
    }
}
