<?php

declare(strict_types=1);

class ScratchCard {
    public function __construct(private array $winningNumbers, private array $personalNumbers) {}

    private function getWinningNubers(): array
    {
        return array_intersect($this->winningNumbers, $this->personalNumbers);
    }

    public function getPoints(): int
    {
        return (int) pow(2, $this->numberOfWinningNumbers() - 1);
    }

    public function numberOfWinningNumbers(): int
    {
        return count($this->getWinningNubers());
    }
}

class PointsCalculator {
    public function __construct(private array $scratchCards) {}

    public function getTotalPoints(): int {
        return array_sum(
                array_map(
                    function($scratchCard) {
                        return $scratchCard->getPoints();
                    },
                    $this->scratchCards
                )
            );
    }

    public function getSecondStarPoints(): int {
        return 0;
    }
}

class InputConverterx {
    private array $scratchCards;
    
    public function __construct(string $input) {
        $this->convertInputToScratchCards($input);
    }

    private function convertInputToScratchCards(string $input)
    {
        $rows = explode(PHP_EOL, $input);

        foreach($rows as $row)
        {
            $this->scratchCards[] = $this->convertRowToScratchCard($row);
        }
    }

    private function convertRowToScratchCard(string $row): ScratchCard
    {
        [$cardIndex, $numbers] = explode(':', $row);
        [$winningNumbers, $personalNumbers] = explode('|', trim($numbers));
        $winningNumbers = array_filter(explode(' ', trim($winningNumbers)));
        $personalNumbers = array_filter(explode(' ', trim($personalNumbers)));

        return new ScratchCard($winningNumbers, $personalNumbers);
    }

    public function getScratchCards(): array
    {
        return $this->scratchCards;
    }
}

class ScratchCardManager {
    private array $scratchCardCounts;

    public function __construct(private array $scratchCards) {
        $this->scratchCardCounts = array_fill_keys(array_keys($this->scratchCards), 1);
    }

    public function getTotalCards(): int
    {
        foreach($this->scratchCards as $key => $scratchCard)
        {
            $cardsToAdd = $scratchCard->numberOfWinningNumbers();
            $this->updateScratchCardCounts($key, $cardsToAdd);
        }

        return array_sum($this->scratchCardCounts);
    }

    public function updateScratchCardCounts(int $index, int $numberOfCardsToAdd): void
    {
        $multiplier = $this->scratchCardCounts[$index];

        for($i = 1; $i <= $numberOfCardsToAdd; $i++)
        {
            if(key_exists($index + $i, $this->scratchCardCounts)) {
                $this->scratchCardCounts[$index + $i] += $multiplier;
            }
        }
    }
}

$inputs = [
    'Test' => file_get_contents('testinput'),
    'Real' => file_get_contents('input')
];

$log = "";

foreach($inputs as $key => $input)
{
    $inputConverter = new InputConverterx($input);
    $pointsCalculator = new PointsCalculator($inputConverter->getScratchCards());
    $cardManager = new ScratchCardManager($inputConverter->getScratchCards());

    $log .= $key.' Input:<br>';
    $log .= '1.⭐ Points: '.$pointsCalculator->getTotalPoints().'<br>';
    $log .= '2.⭐ Points: '.$cardManager->getTotalCards().'<br><br>';
}

$dir = __DIR__;
