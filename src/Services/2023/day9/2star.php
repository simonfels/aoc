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

function visualize(array $diff, int $counter)
{
    echo "<tr>";
    if($counter > 0)
    {
        echo "<td colspan=".($counter)."></td>";
    }
    foreach($diff as $d) {
        echo "<td colspan='2' style='border: black solid 1px; text-align: center'>$d</td>";
    }
    echo "</tr>";
}

$remembers = 0;

foreach($input_rows as $input_row)
{
    $numbers = explode(" ", $input_row);

    $diffs = [$numbers[0]];


    $counter = 0;
    echo "<table>";
    foreach($numbers as $a) { echo "<td></td>"; }; foreach($numbers as $a) { echo "<td></td>"; };
    visualize($numbers, $counter);
    do {
        $currentDiff = diff($numbers);
        $counter++;
        visualize($currentDiff, $counter);
        $diffs[] = $currentDiff[0];
        $numbers = $currentDiff;
    } while(sizeof($currentDiff) !== sizeof(array_filter($currentDiff, fn($a) => $a == 0)));
    echo "</table>";

    Logger::append("<br>".implode("; ", $diffs));

    $alterator = false;
    $remember = 0;
    foreach($diffs as $fidd)
    {
        if($alterator)
        {
            $alterator = false;
            $remember -= $fidd;
        } else {
            $alterator = true;
            $remember += $fidd;
        }
    }
    $remembers += $remember;
}

Logger::append("<br><br>". $remembers);

$dir = __DIR__;

include_once __DIR__ . '/../index.php';