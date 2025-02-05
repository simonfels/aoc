<?php

declare(strict_types=1);

namespace AoC;

use AoC\TransformRule;

class Transformer
{
    public TransformFunction $transformFunction;
    public array $transformSteps;

    public function __construct()
    {
        $this->transformSteps = [];
        $this->transformFunction = new TransformFunction();
    }

    public function addTransformStep(): void
    {
        $this->transformSteps[] = [];
    }

    public function addTransformRule(TransformRule $transformRule): void
    {
        $this->transformSteps[sizeof($this->transformSteps) - 1][] = $transformRule;
    }

    public function mergeSteps(): void
    {
        foreach ($this->transformSteps as $transformStep)
        {
            $this->transformFunction->merge($transformStep);
        }
    }
}
