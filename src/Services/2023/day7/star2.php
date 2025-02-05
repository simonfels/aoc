<?php

declare(strict_types=1);

class Hand {
    function __construct(
        public int $cardScore,
        public int $winningRuleId,
        public string $originalHand,
        public string $sortedHand,
        public string $bid
    ) {}

    public function generateIndex(): string
    {
        return $this->HandScore() . str_pad((string )$this->cardScore, 7, '0', STR_PAD_LEFT);
    }

    public function HandScore(): int
    {
        return sizeof(RULESETS) - $this->winningRuleId;
    }

    public function getResult(): string
    {
        return $this->bid;
    }

    public function debug(): string
    {
        return
            $this->originalHand.' | '.
            $this->sortedHand.' | '.
            $this->generateIndex().' | '.
            $this->cardScore.' | '.
            $this->winningRuleId;
    }
}

const ORDER = [
    'A' => 'a',
    'K' => 'b',
    'Q' => 'c',
    'T' => 'd',
    '9' => 'e',
    '8' => 'f',
    '7' => 'g',
    '6' => 'h',
    '5' => 'i',
    '4' => 'j',
    '3' => 'k',
    '2' => 'l',
    'J' => 'm'
];

const RULESETS = [
    0 => ['/(.)\1\1\1\1/'], // 5 Gleiche
    1 => ['/(.)\1\1\1/'], // 4 Gleiche
    2 => ['/(.)\1\1(.)\2/', '/(.)\1(.)\2\2/'], // Full House
    3 => ['/(.)\1\1/'], // 3 Gleiche
    4 => ['/(.)\1.*(.)\2/'], // 2 * 2 Gleiche
    5 => ['/(.)\1/'], // 2 Gleiche
];

const JCONVERTS = [
    0 => [0 => 0, 5 => 0],
    1 => [0 => 1, 1 => 0, 4 => 0],
    2 => [0 => 2, 2 => 0, 3 => 0],
    3 => [0 => 3, 1 => 1, 2 => 0, 3 => 1],
    4 => [0 => 4, 1 => 2, 2 => 1],
    5 => [0 => 5, 1 => 3, 2 => 3],
    6 => [0 => 6, 1 => 5]
];

function CalculateCardScorea($cards): int
{
    $SINGLEHANDSCORE = array_flip(array_reverse(array_values(ORDER)));
    $score = 0;
    $handSize = sizeof($cards);

    foreach($cards as $index => $card) {
        $score += $SINGLEHANDSCORE[$card] * pow(13, $handSize - $index);
    }

    return $score;
}

function CalculateHandScore(string $hand): int
{
    $winningRule = ReplaceJokers($hand, GetWinningRule($hand));

    return $winningRule;
}

function ReplaceJokers(string $hand, $winningRuleId): int
{
    preg_match("/m+/", $hand, $numberOfJokers);


    if(empty($numberOfJokers)) {
        return $winningRuleId;
    }

    return JCONVERTS[$winningRuleId][strlen($numberOfJokers[0])];
}

function GetWinningRule(string $hand): int
{
    foreach(RULESETS as $index => $ruleSet)
    {
        foreach($ruleSet as $rule)
        {
            preg_match($rule, $hand, $matches);

            if(!empty($matches))
            {
                return $index;
            }
        }
    }

    return 6;
}

function GenerateHand(string $hand, string $bid): Hand
{
    $cards = str_split($hand, 1);
    $cards = array_map(fn($card) => ORDER[$card], $cards);
    $cardScore = CalculateCardScorea($cards);
    sort($cards);
    $sortedHand = implode($cards);
    $winningRuleId = CalculateHandScore($sortedHand);

    $hand = new Hand($cardScore, $winningRuleId, $hand, $sortedHand, $bid);
    
    return $hand;
}

$rows = explode(PHP_EOL, file_get_contents(__DIR__ . "/input"));
$rows = array_map(fn($row) => explode(" ", $row), $rows);

$scores = [];

foreach($rows as $row)
{
    [$hand, $bid] = $row;

    $hand = GenerateHand($hand, $bid);
    $scores[$hand->generateIndex()] = $hand;
}

ksort($scores, SORT_STRING);

$scores = array_values($scores);

$result = 0;

foreach($scores as $index => $hand)
{
    $result += ($index + 1) * $hand->bid;
}

$log = "";
$log .= $result;

$dir = __DIR__;

include_once __DIR__ . '/../index.php';