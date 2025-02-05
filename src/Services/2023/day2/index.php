<?php

declare(strict_types=1);

$input = explode(PHP_EOL, file_get_contents('input'));

// first & second star

$limit = [
    'red' => 12,
    'green' => 13,
    'blue' => 14
];

$sumOfPossibleIds = 0;
$sumOfSetPower = 0;

foreach($input as $game) {
    [$gameId, $turns] = explode(':', trim($game));
    
    preg_match('/\d+/', $gameId, $match);
    
    $gameId = (int) $match[0];
    $possible = true;
    $maxCubes = [];
    
    foreach(explode(';', $turns) as $turn) {
        foreach(explode(',', trim($turn)) as $hand) {
            [$count, $color] = explode(' ', trim($hand));
            if($limit[$color] < $count) {
                $possible = false;
            }
            if(empty($maxCubes[trim($color)]) || $maxCubes[trim($color)] < (int) $count) {
                $maxCubes[trim($color)] = (int) $count;
            }
        }
    }
    
    if($possible) {
        $sumOfPossibleIds += $gameId;
    }
    $sumOfSetPower += array_reduce($maxCubes, function ($carry, $number) { $carry *= $number; return $carry; }, 1);
}

// output

$log = "";
$log .= 'Sum of possible IDs: <pre>'.$sumOfPossibleIds.'</pre>';
$log .= '<br>';
$log .= 'Sum of set powers: <pre>'.$sumOfSetPower.'</pre>';
$dir = __DIR__;

include_once __DIR__ . '/../index.php';