<?php

declare(strict_types=1);

use App\Services\Logger;

include_once __DIR__ . "/../../../../src/autoloader.php";

$inputFiles = ["real" => "input", "test" => "testinput"];
$input_rows = explode(PHP_EOL, file_get_contents(__DIR__ ."/". $inputFiles["real"]));

function diff(array $array): array
{
    $firstNumber = (int) array_shift($array);
    $diff = [];

    foreach($array as $number)
    {
        $diff[] = $number - $firstNumber;
        $firstNumber = $number;
    }

    return $diff;
}

$remembers = 0;

foreach($input_rows as $input_row)
{
    $numbers = explode(" ", $input_row);

    $diffs = [$numbers[array_key_last($numbers)]];

    do {
        $currentDiff = diff($numbers);
        $diffs[] = $currentDiff[array_key_last($currentDiff)];
        $numbers = $currentDiff;
    } while(sizeof($currentDiff) !== sizeof(array_filter($currentDiff, fn($a) => $a == 0)));

    $remembers += array_sum($diffs);
}

Logger::append("<br><br>". $remembers);

$dir = __DIR__;

include_once __DIR__ . '/../index.php';