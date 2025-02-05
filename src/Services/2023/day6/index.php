<?php

declare(strict_types=1);

$rows = explode(PHP_EOL, file_get_contents(__DIR__ . "/input"));
preg_match_all("/\d+/", $rows[0], $times);
preg_match_all("/\d+/", $rows[1], $bestDistances);

$input = array_combine($times[0], $bestDistances[0]);
$result = 1;

foreach($input as $time => $bestDistance)
{
    $result *= getBetterResults((int) $time, (int) $bestDistance);
}

echo $result;

function getBetterResults(int $time, int $bestDistance) {
    $x1 = ((-$time) + sqrt(pow($time, 2) - 4 * $bestDistance)) / -2;
    $x2 = ((-$time) - sqrt(pow($time, 2) - 4 * $bestDistance)) / -2;

    return ceil($x2 - 1) - floor($x1 + 1) + 1;
}


# ----------- Star 2

preg_match("/\d+/", str_replace(" ", "", $rows[0]), $time);
preg_match("/\d+/", str_replace(" ", "", $rows[1]), $bestDistance);

$log = "";
$log .= "$time[0] | $bestDistance[0] | ";
$log .= getBetterResults((int) $time[0], (int) $bestDistance[0]);

$dir = __DIR__;

include_once __DIR__ . '/../index.php';