<?php

declare(strict_types=1);

$inputFiles = ["real" => "input", "test" => "testinput"];
$input_rows = explode(PHP_EOL, file_get_contents(__DIR__ . "/" . $inputFiles["real"]));

$instructionLine = array_shift($input_rows);
$instructions = str_split($instructionLine);

array_shift($input_rows); // shift empty line

class BinaryPath
{
    public ?BinaryPath $left = null;
    public ?BinaryPath $right = null;

    function __construct(public string $id){}
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

$current = $paths["AAA"];

$counter = 0;

do {
    foreach($instructions as $line)
    {
        $counter++;
        //$log .= "$current->id<br>";
        $newCurrent = $line == "L" ? $current->left : $current->right;
        $current = $newCurrent;
    }
} while($current->id != "ZZZ");

$log = "Iterations: $counter";

$dir = __DIR__;

include_once __DIR__ . '/../index.php';