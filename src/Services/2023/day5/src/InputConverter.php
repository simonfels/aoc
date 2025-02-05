<?php

declare(strict_types=1);

namespace AoC;

use AoC\Transformer;
use AoC\TransformRule;

class InputConverter
{
    private array $seeds;
    private Transformer $transformer;

    public function __construct(private string $puzzleInput)
    {
        $this->convert();
    }

    private function convert(): void
    {
        $puzzleRows = explode(PHP_EOL, $this->puzzleInput);

        $this->getSeedsFromPuzzleInput(array_shift($puzzleRows));

        $this->transformer = new Transformer();

        foreach($puzzleRows as $row)
        {
            if(empty($row)) {
                continue;
            }

            if (preg_match('/(map)/', $row))
            {
                $this->transformer->addTransformStep();
                continue;
            }

            $transformRule = TransformRule::createFromString($row);

            $this->transformer->addTransformRule($transformRule);
        }

        $this->transformer->mergeSteps();

        print_r($this->transformer->transformFunction);
    }

    private function getSeedsFromPuzzleInput(string $seedsRow): void
    {
        $seedsString = substr($seedsRow, 7);
        $seedsArray = explode(' ', $seedsString);
        $seedsIntArray = array_map('intval', $seedsArray);

        $this->seeds = array_chunk($seedsIntArray, 2);
    }

    public function getSeedManagerInput(): array
    {
        return [$this->seeds, $this->transformer];
    }
}
