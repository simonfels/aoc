<?php

declare(strict_types=1);

class Hand {
    function __construct(
        public int $cardScore,
        public int $handScore,
        public string $handRule,
        public string $originalHand,
        public string $sortedHand,
        public string $bid
    ) {}

    public function generateIndex(): string
    {
        return $this->handScore . str_pad((string )$this->cardScore, 7, '0', STR_PAD_LEFT);
    }

    public function getResult(): string
    {
        return $this->bid;
    }
}

const ORDER = [
    'A' => 'a',
    'K' => 'b',
    'Q' => 'c',
    'J' => 'd',
    'T' => 'e',
    '9' => 'f',
    '8' => 'g',
    '7' => 'h',
    '6' => 'i',
    '5' => 'j',
    '4' => 'k',
    '3' => 'l',
    '2' => 'm'
];

const RULESETS = [
    ['/(.)\1\1\1\1/'],
    ['/(.)\1\1\1/'],
    ['/(.)\1\1(.)\2/', '/(.)\1(.)\2\2/'],
    ['/(.)\1\1/'],
    ['/(.)\1.*(.)\2/'],
    ['/(.)\1/'],
];

function CalculateCardScore($cards): int
{
    $SINGLEHANDSCORE = array_flip(array_reverse(array_values(ORDER)));
    $score = 0;
    $handSize = sizeof($cards);

    foreach($cards as $index => $card) {
        $score += $SINGLEHANDSCORE[$card] * pow(13, $handSize - $index);
    }

    return $score;
}

function CalculateHandScore(string $hand): array
{
    $matches = [];
    $winningRuleId = 6;
    $winningRule = '';

    foreach(RULESETS as $index => $ruleSet)
    {
        foreach($ruleSet as $rule)
        {
            preg_match($rule, $hand, $matches);

            if(!empty($matches))
            {
                $winningRuleId = $index;
                $winningRule = $rule;
                break;
            }
        }

        if(!empty($matches))
        {
            break;
        }
    }

    return [sizeof(RULESETS) - $winningRuleId, $winningRule];
}

function GenerateHand(string $hand, string $bid): Hand
{
    $cards = str_split($hand, 1);
    $cards = array_map(fn($card) => ORDER[$card], $cards);
    $cardScore = CalculateCardScore($cards);
    sort($cards);
    $sortedHand = implode($cards);
    $handScore = CalculateHandScore($sortedHand);

    $hand = new Hand($cardScore, $handScore[0], $handScore[1], $hand, $sortedHand, $bid);
    
    return $hand;
}

$rows = explode(PHP_EOL, file_get_contents(__DIR__ . "/input"));
$rows = array_map(fn($row) => explode(" ", $row), $rows);

$scores = [];

foreach($rows as $row)
{
    [$hand, $bid] = $row;

    $hand = GenerateHand($hand, $bid);
    $scores[$hand->generateIndex()] = $hand->getResult();
}

ksort($scores, SORT_STRING);

$scores = array_values($scores);

$result = 0;

foreach($scores as $index => $bid)
{
    $result += ($index + 1) * $bid;
}

$log = "";
$log .= $result;

$dir = __DIR__;

include_once __DIR__ . '/../index.php';