<?php

declare(strict_types=1);

$inputFiles = ["real" => "input", "test" => "2testinput"];
$input_rows = explode(PHP_EOL, file_get_contents(__DIR__ . "/" . $inputFiles["real"]));

$instructionLine = array_shift($input_rows);
$instructions = str_split($instructionLine);

array_shift($input_rows); // shift empty line

class BinaryPath
{
    public ?BinaryPath $left = null;
    public ?BinaryPath $right = null;

    function __construct(public string $id){}

    function next(string $instruction): BinaryPath|null
    {
        return $instruction == "R" ? $this->right : $this->left;
    }
}

$paths = [];

foreach($input_rows as $input_row)
{
    $start = substr($input_row, 0, 3);
    $left = substr($input_row, 7, 3);
    $right = substr($input_row, 12, 3);
    
    $startPath = $paths[$start] ?? new BinaryPath($start);
    $paths[$startPath->id] = $startPath;

    $leftPath = $paths[$left] ?? new BinaryPath($left);
    $startPath->left = $leftPath;
    $paths[$leftPath->id] = $leftPath;

    $rightPath = $paths[$right] ?? new BinaryPath($right);
    $startPath->right = $rightPath;
    $paths[$rightPath->id] = $rightPath;
}

function pathEndsWith(BinaryPath $path, string $endsWith): bool
{
    return substr($path->id, 2, 1) == $endsWith;
}

function isStart(BinaryPath $path)
{
    return pathEndsWith($path, "A");
}

function isEnd(BinaryPath $path)
{
    return pathEndsWith($path, "Z");
}

$currents = array_filter($paths, "isStart");

$ends = [];

// Get the least number of iterations to get to an end per starting path
foreach($currents as $current)
{
    $instructionIterations = 0;
    $end = null;

    while($end == null) {
        $instructionIterations++;
        foreach($instructions as $instruction)
        {
            $tempCurrent = $current->next($instruction);
            $current = $tempCurrent;

            if(isEnd($current)) {
                $end = $instructionIterations;
            }
        }
    }

    $ends[] = $end;
}

// Calculate the least common multiplier of all "Starts"
$result = 1;
foreach($ends as $end)
{
    $oldResult = $result;
    $result = gmp_lcm($oldResult, $end). "\n";
}

// Multiply by Number of Instruction-Steps to get the total Steps needed
$log = "Iterations: ".$result * strlen($instructionLine);

$dir = __DIR__;

include_once __DIR__ . '/../index.php';
