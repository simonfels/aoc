<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__.'/input');
$testinput = file_get_contents(__DIR__.'/testinput');

class PartNumberSchematic
{
    private array $rows;
    private int $height;
    private int $width;

    public function __construct(string $input)
    {
        $inputRows = explode(PHP_EOL, $input);
        $this->rows = $inputRows;
        $this->height = sizeof($inputRows);
        $this->width = strlen($inputRows[0]);
    }

    public function sumOfPartNumbers(): int
    {
        $partNumbers = 0;
        
        foreach ($this->rows as $rowIndex => $row)
        {
            preg_match_all('/\d+/', $row, $matches, PREG_OFFSET_CAPTURE);

            $partNumbers += $this->extractValidPartNumbersFromMatches($matches, $rowIndex);
        }

        return $partNumbers;
    }

    public function sumOfGearRatios(): int
    {
        $symbolsArr = [];
        foreach ($this->rows as $rowIndex => $row)
        {
            preg_match_all('/\d+/', $row, $partNumbers, PREG_OFFSET_CAPTURE);

            foreach($partNumbers[0] as $partNumber)
            {
                [$number, $position] = $partNumber;
                $symbols = $this->extractAdjacentSymbols($rowIndex, $position, $number, '/[\*]/');
                
                if(!empty($symbols))
                {
                    // partNumber has adjacent * symbol
                    foreach($symbols as $symbol)
                    {
                        $arrayKey = $symbol[0] . '$' . $symbol[1];
                        if(!empty($symbolsArr[$arrayKey]))
                        {
                            $symbolsArr[$arrayKey] = array_merge($symbolsArr[$arrayKey], [$symbol[2]]);
                        } else {
                            $symbolsArr[$arrayKey] = [$symbol[2]];
                        }
                    }
                }
            }
        }

        $symbolsArr = array_filter($symbolsArr, fn($partNumbers) => count($partNumbers) == 2);
        $symbolsArr = array_map(fn($partNumbers) => array_reduce($partNumbers, fn($partNumber, $carrier) => $partNumber * $carrier, 1), $symbolsArr);

        return array_sum($symbolsArr);
    }

    private function extractValidPartNumbersFromMatches(array $matches, int $rowIndex): int
    {
        $validMatches = array_filter($matches[0], fn ($match) => $this->matchHasAdjacentSymbol($rowIndex, $match[1], $match[0], '/[^0-9^\.]/'));

        return array_sum(array_map(fn($match) => (int) $match[0], $validMatches));
    }

    private function matchHasAdjacentSymbol(int $rowIndex, int $columnIndex, string $word, string $pattern): bool
    {
        return !empty($this->extractAdjacentSymbols($rowIndex, $columnIndex, $word, $pattern));
    }

    private function extractAdjacentSymbols(int $rowIndex, int $columnIndex, string $word, string $pattern): array
    {
        $wordLength = strlen($word);
        $rowStartId = max($rowIndex - 1, 0);
        $rowEndId = min($rowIndex + 1, $this->height - 1);
        $columnStart = max($columnIndex - 1, 0);
        $columnEnd = min($columnIndex + $wordLength, $this->width);
        $columnWidth = (int) ($columnEnd - $columnStart + 1);

        $matchesArr = [];

        foreach (range($rowStartId, $rowEndId) as $rowId)
        {
            $partOfRow = substr($this->rows[$rowId], $columnStart, $columnWidth);

            preg_match($pattern, $partOfRow, $matches, PREG_OFFSET_CAPTURE);

            // save position
            if(!empty($matches))
            {
                $matchesArr[] = [$rowId, ($matches[0][1] + $columnStart), $word];
            }
            
        }

        return $matchesArr;
    }
}

$log = "";
$log .= "Test Input:<br>";
$log .= 'Sum of Partnumbers: '.(new PartNumberSchematic($testinput))->sumOfPartNumbers().'<br>';
$log .= 'Sum of Gear Ratios: '.(new PartNumberSchematic($testinput))->sumOfGearRatios();

$log .= "<br><br>Real Input:<br>";
$log .= 'Sum of Partnumbers: '.(new PartNumberSchematic($input))->sumOfPartNumbers().'<br>';
$log .= 'Sum of Gear Ratios: '.(new PartNumberSchematic($input))->sumOfGearRatios();
$dir = __DIR__;

include_once __DIR__ . '/../index.php';