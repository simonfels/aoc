<?php

declare(strict_types=1);

set_time_limit(15);

class InputConverter
{
    private array $seeds;
    private SourceDestinationMapList $sourceDestinationMapList;

    public function __construct(private string $input)
    {
        $this->convert();
    }

    private function convert(): void
    {
        $rows = explode(PHP_EOL, $this->input);
        $this->seeds = array_map(fn($seed) => (int) $seed, explode(' ', substr(array_shift($rows), 7)));
        $this->sourceDestinationMapList = new SourceDestinationMapList();

        foreach($rows as $row)
        {
            if(empty($row)) {
                continue;
            }

            if (preg_match('/(map)/', $row))
            {
                $currentSourceDestinationMap = new SourceDestinationMap();
                $this->sourceDestinationMapList->addSourceDestinationMap($currentSourceDestinationMap);
                continue;
            }

            $newMapSet = new MapSet(...array_map(fn ($number) => (int) $number, explode(' ', $row)));
            $currentSourceDestinationMap->addMapSet($newMapSet);
        }
    }

    public function getSeeds(): array
    {
        return $this->seeds;
    }

    public function getSourceDestinationMapList(): SourceDestinationMapList
    {
        return $this->sourceDestinationMapList;
    }
}

class MapSet
{
    private int $sourceRangeEnd;
    private int $mappingOffset;

    public function __construct(
        public int $destinationRangeStart,
        public int $sourceRangeStart,
        public int $range
    ) {
        $this->sourceRangeEnd = $sourceRangeStart + $range - 1;
        $this->mappingOffset = $sourceRangeStart - $destinationRangeStart;
    }

    public function numberIsInRange(int $number): bool
    {
        return ($this->sourceRangeStart <= $number && $number <= $this->sourceRangeEnd);
    }

    public function map(int $number): int
    {
        return $number - $this->mappingOffset;
    }
}

class SourceDestinationMap
{
    public function __construct(private array $mapSets = [])
    {
    }

    public function addMapSet(MapSet $mapSet)
    {
        $this->mapSets[] = $mapSet;
    }

    public function map(int $number): int
    {
        return $this->getDestination($number) ?? $number;
    }

    private function getDestination(int $number): null|int
    {
        foreach($this->mapSets as $mapSet)
        {
            if($mapSet->numberIsInRange($number))
            {
                return $mapSet->map($number);
            }
        }

        return null;
    }
}

class SourceDestinationMapList
{
    public function __construct(private array $sourceDestinationMaps = [])
    {
    }

    public function addSourceDestinationMap(SourceDestinationMap $map): void
    {
        $this->sourceDestinationMaps[] = $map;
    }

    public function map(int $number): int
    {
        foreach($this->sourceDestinationMaps as $sourceDestinationMap)
        {
            $number = $sourceDestinationMap->map($number);
        }

        return $number;
    }
}

class SeedManager
{
    private string $filePath;

    public function __construct(private array $seeds, private SourceDestinationMapList $sourceDestinationMapList, string $env)
    {
        $this->filePath = '/var/www/public_html/aoc/day5/currentlowest'.$env;
    }

    public function getLowestLocationNumber(): int
    {
        foreach($this->seeds as $seed)
        {
            $seedLocation = $this->sourceDestinationMapList->map($seed);

            if(empty($lowestSeedLocation) || $lowestSeedLocation > $seedLocation)
            {
                $lowestSeedLocation = $seedLocation;
            }
        }

        return $lowestSeedLocation;
    }
    
    public function getLowestLocationNumber2(): int
    {
        $seedPairs = array_chunk($this->seeds, 2);
        [$initialSeedPairIndex, $initialSeed, $intialLowestSeedLocation] = explode(' ', file_get_contents($this->filePath));

        $lowestSeedLocation = ((int) $intialLowestSeedLocation) ?? 9223372036854775807;

        for($seedPairIndex = ((int) $initialSeedPairIndex) ?? 0; $seedPairIndex < sizeof($seedPairs); $seedPairIndex++)
        {
            [$seedStart, $seedRange] = $seedPairs[$seedPairIndex];

            if(!empty($initialSeed))
            {
                $acutalStart = (int) $initialSeed + 1;
                unset($initialSeed);
            }

            $acutalStart = $seedStart;

            for($seed = $acutalStart; $seed < ($seedStart + $seedRange); $seed++)
            {
                $seedLocation = $this->sourceDestinationMapList->map($seed);

                if($lowestSeedLocation > $seedLocation)
                {
                    $lowestSeedLocation = $seedLocation;
                }

                if($seed % 1000000 == 0)
                {
                    file_put_contents($this->filePath, "$seedPairIndex $seed $lowestSeedLocation");
                }
            }
        }

        return $lowestSeedLocation;
    }
}

$inputs = [
    #'Test' => file_get_contents('testinput'),
    #'Real' => file_get_contents('input')
];

$log = "";

foreach($inputs as $key => $input)
{
    $inputConverter = new InputConverter($input);
    $seedManager = new SeedManager($inputConverter->getSeeds(), $inputConverter->getSourceDestinationMapList(), $key);

    $log .= $key.' Input:<br>';
    #$log .= '1.⭐ Answer: '.$seedManager->getLowestLocationNumber().'<br>';
    $log .= '2.⭐ Answer: '.$seedManager->getLowestLocationNumber2().'<hr>';
}
$log .= "WIP";

$dir = __DIR__;

include_once __DIR__ . '/../index.php';
