<?php

declare(strict_types=1);

namespace App\Structs\Y2024\Day2;

class Report
{
    public readonly array $levels;

    public function __construct(
        string $levelString
    ) {
        $this->levels = explode(" ", $levelString);
    }
}
