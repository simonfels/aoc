<?php

declare(strict_types=1);

use App\Services\Logger;
use App\Controller\AocController;
use App\Structs\AoC\Y2023\Day10\CoordinateSystem;

include_once __DIR__ . "/../../../../src/autoloader.php";

$inputFiles = ['test' => '2testinput', 'real' => 'input'];

if(!empty($_GET['puzzleInput']))
{
    $puzzleInput = htmlspecialchars($_GET['puzzleInput'], ENT_QUOTES, 'UTF-8');
}

$puzzleInput ??= 'test';

$input_rows = explode(PHP_EOL, file_get_contents(__DIR__ .'/'. $inputFiles[$puzzleInput]));

$coordSystem = new CoordinateSystem($input_rows);
$totalDistance = $coordSystem->calculateTotalDistance();

Logger::append("Half Distance: $totalDistance / 2 = ".$totalDistance/2);
Logger::append("<br>Left Turns vs. Right Turns: ".$coordSystem->leftTurns." | ".$coordSystem->rightTurns." | Left inside: ".($coordSystem->leftInside() ? 'Ja' : 'Nein'));
Logger::append("<div style='white-space: pre-line'>$coordSystem</div>");

(new AocController(__FILE__))->index(Logger::read(), ["currentPuzzleInput" => $puzzleInput]);
