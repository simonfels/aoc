<?php

declare(strict_types=1);

namespace AoC;

use AoC\Transformer;

class SeedManager
{
    public function __construct(string $env, private array $seeds, private Transformer $transformer)
    {
    }

    public function getLowestLocationNumber(): int
    {
        foreach($this->seeds as $seedPair)
        {
            [$seedStart, $seedRange] = $seedPair;

            $seedLocation = $this->transformer->transformFunction->map($seedStart, $seedRange);

            if(empty($lowestSeedLocation) || $lowestSeedLocation > $seedLocation)
            {
                $lowestSeedLocation = $seedLocation;
            }
        }

        return $lowestSeedLocation;
    }
}
