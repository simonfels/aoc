<?php

declare(strict_types=1);

namespace AoC;

class TransformRule
{
    public int $destinationStart;
    public int $destinationEnd;

    public function __construct(
        public int $sourceStart,
        public int $sourceEnd,
        public int $mappingOffset,
        private array $destinationOverlaps = []
    )
    {
        $this->destinationStart = $this->getDestinationStart();
        $this->destinationEnd = $this->getDestinationEnd();
    }

    public function getRange(): int
    {
        return $this->sourceEnd - $this->sourceStart + 1;
    }

    public function getDestinationStart(): int
    {
        return $this->sourceStart + $this->mappingOffset;
    }

    public function getDestinationEnd(): int
    {
        return $this->sourceEnd + $this->mappingOffset;
    }

    public function overlapsWith(TransformRule $transformRule): string
    {
        if($this->sourceStart <= $transformRule->getDestinationStart() && $this->sourceEnd >= $transformRule->getDestinationStart())
        {
            //$transformRule->so
        }
            ($this->sourceStart <= $transformRule->getDestinationEnd() && $this->sourceEnd >= $transformRule->getDestinationEnd()) ||
            ($this->sourceStart >= $transformRule->getDestinationStart() && $this->sourceStart <= $transformRule->getDestinationEnd());
    }

    static public function createFromString(string $inputString): TransformRule
    {
        [$destinationStart, $sourceStart, $range] = array_map('intval', explode(' ', $inputString));
        $sourceEnd = $sourceStart + $range - 1;
        $mappingOffset = $destinationStart - $sourceStart;

        return new TransformRule($sourceStart, $sourceEnd, $mappingOffset);
    }
}
