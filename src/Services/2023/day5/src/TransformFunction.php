<?php

declare(strict_types=1);

namespace AoC;

use AoC\TransformRule;

class TransformFunction
{
    private array $functionRules;

    public function merge(array $transformStepRules): void
    {
        foreach ($transformStepRules as $transformRule)
        {
            /** @var TransformRule $transformRule */

            $this->mergeTransformRule($transformRule);
        }

        ksort($this->functionRules);
    }

    private function mergeTransformRule(TransformRule $transformRule): void
    {
        if(empty($this->functionRules))
        {
            $this->addRule($transformRule);
        }

        foreach ($this->functionRules as $functionRule)
        {
            if (!$transformRule->overlapsWith($functionRule))
            {
                continue;
            }

            //$functionRule->source;
        }
    }

    private function addRule(TransformRule $transformRule): void
    {
        $this->functionRules[$transformRule->sourceStart] = $transformRule;
    }

    public function map(int $seedStart, int $seedRange): int
    {
        $relevantRules = [];

        foreach($this->functionRules as $functionRule)
        {
            $start = $seedStart;
            $end = $seedStart + $seedRange - 1;

            if(
                ($start >= $functionRule->sourceStart && $start <= $functionRule->sourceEnd) ||
                ($end >= $functionRule->sourceStart && $end <= $functionRule->sourceEnd) ||
                ($start <= $functionRule->sourceStart && $end >= $functionRule->sourceEnd)
            )
            {
                $relevantRules[max($functionRule->sourceStart, $start)] = $functionRule;
            }
        }

        if(empty($relevantRules))
        {
            return $seedStart;
        }

        uasort($relevantRules, fn($a, $b) => $a->mappingOffset <=> $b->mappingOffset);

        $intersection = array_key_first($relevantRules);

        return $intersection + $relevantRules[$intersection]->mappingOffset;
    }
}
