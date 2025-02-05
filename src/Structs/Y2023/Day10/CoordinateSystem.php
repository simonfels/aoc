<?php

declare(strict_types=1);

namespace App\Structs\Y2023\Day10;

class CoordinateSystem {
    public array $coordinateArray;
    public int $leftTurns = 0;
    public int $rightTurns = 0;

    private const TOP_RIGHT = 'L';
    private const TOP_LEFT = 'J';
    private const BOTTOM_RIGHT = 'F';
    private const BOTTOM_LEFT = '7';
    private const TOP_BOTTOM = '|';
    private const LEFT_RIGHT = '-';

    function __construct(
        private array $coordArrays
    ) {
        foreach($this->coordArrays as $y => $coordArray)
        {
            foreach(str_split($coordArray) as $x => $coord)
            {
                $coord = new Coordinate($x, $y, $coord, -1, "up", $this);
                $this->coordinateArray[$coord->key] = $coord;
            }
        }
    }

    public function leftInside(): bool
    {
        return $this->leftTurns > $this->rightTurns;
    }

    private function connects(string $command, string $firstInput, string $secondInput): bool
    {
        if(empty($this->coordinateArray[$firstInput]) || empty($this->coordinateArray[$secondInput]))
        {
            return false;
        }

        $firstCoord = $this->coordinateArray[$firstInput];
        $secondCoord = $this->coordinateArray[$secondInput];

        return match($command) {
            'up' => $this->connectsUp($firstCoord, $secondCoord),
            'down' => $this->connectsDown($firstCoord, $secondCoord),
            'left' => $this->connectsLeft($firstCoord, $secondCoord),
            'right' => $this->connectsRight($firstCoord, $secondCoord)
        };
    }

    private function connectsUp(Coordinate $firstCoord, Coordinate $secondCoord): bool
    {
        return ($firstCoord->y - 1) ==  $secondCoord->y &&
            in_array($firstCoord->tile, ['S', self::TOP_RIGHT, self::TOP_LEFT, self::TOP_BOTTOM]) &&
            in_array($secondCoord->tile, ['S', self::BOTTOM_RIGHT, self::BOTTOM_LEFT, self::TOP_BOTTOM]);
    }

    private function connectsDown(Coordinate $firstCoord, Coordinate $secondCoord): bool
    {
        return ($firstCoord->y + 1) ==  $secondCoord->y &&
            in_array($firstCoord->tile, ['S', self::BOTTOM_RIGHT, self::BOTTOM_LEFT, self::TOP_BOTTOM]) &&
            in_array($secondCoord->tile, ['S', self::TOP_RIGHT, self::TOP_LEFT, self::TOP_BOTTOM]);
    }

    private function connectsLeft(Coordinate $firstCoord, Coordinate $secondCoord): bool
    {
        return ($firstCoord->x - 1) ==  $secondCoord->x &&
            in_array($firstCoord->tile, ['S', self::TOP_LEFT, self::BOTTOM_LEFT, self::LEFT_RIGHT]) &&
            in_array($secondCoord->tile, ['S', self::TOP_RIGHT, self::BOTTOM_RIGHT, self::LEFT_RIGHT]);
    }

    private function connectsRight(Coordinate $firstCoord, Coordinate $secondCoord): bool
    {
        return ($firstCoord->x + 1) ==  $secondCoord->x &&
            in_array($firstCoord->tile, ['S', self::TOP_RIGHT, self::BOTTOM_RIGHT, self::LEFT_RIGHT]) &&
            in_array($secondCoord->tile, ['S', self::TOP_LEFT, self::BOTTOM_LEFT, self::LEFT_RIGHT]);
    }

    private function setStart(): ?Coordinate
    {
        $tiles = array_combine(array_keys($this->coordinateArray), array_column($this->coordinateArray, 'tile'));

        if($search = array_search("S", $tiles))
        {
            return $this->coordinateArray[$search];
        }

        return null;
    }

    function calculateTotalDistance(): int
    {
        $startPosition = $this->setStart();
        
        $currentPosition = $startPosition;
        $blockedPathKey = null;
        $distance = 0;
        $orientation = null;

        while($currentPosition->key != $startPosition->key || $blockedPathKey == null)
        {
            $distance++;
            
            if($currentPosition->up()->key != $blockedPathKey && $this->connects('up', $currentPosition->key, $currentPosition->up()->key))
            {
                $blockedPathKey = $currentPosition->key;
                $orientation = 'up';
                $currentPosition = $currentPosition->up();
            }
            elseif($currentPosition->right()->key != $blockedPathKey && $this->connects('right', $currentPosition->key, $currentPosition->right()->key))
            {
                $blockedPathKey = $currentPosition->key;
                $orientation = 'right';
                $currentPosition = $currentPosition->right();
            }
            elseif($currentPosition->down()->key != $blockedPathKey && $this->connects('down', $currentPosition->key, $currentPosition->down()->key))
            {
                $blockedPathKey = $currentPosition->key;
                $orientation = 'down';
                $currentPosition = $currentPosition->down();
            }
            elseif($currentPosition->left()->key != $blockedPathKey && $this->connects('left', $currentPosition->key, $currentPosition->left()->key))
            {
                $blockedPathKey = $currentPosition->key;
                $orientation = 'left';
                $currentPosition = $currentPosition->left();
            }

            $this->coordinateArray[$currentPosition->key]->setDistance($distance);
            $this->setSides($orientation, $currentPosition);
        }

        

        return $distance;
    }

    private function setSides(string $lastStep, Coordinate $currentPosition)
    {
        $currentPosition = $this->coordinateArray[$currentPosition->key];

        switch($lastStep) {
            case "up":
                $currentPosition->orientation = match($currentPosition->tile) {
                    self::BOTTOM_LEFT => "left",
                    self::BOTTOM_RIGHT => "right",
                    default => "up"
                };
                if($currentPosition->orientation == "up") {
                    break;
                }
                if($currentPosition->orientation == "left") {
                    $this->leftTurns++;
                }
                if($currentPosition->orientation == "right") {
                    $this->rightTurns++;
                }
                break;
            case "right":
                $currentPosition->orientation = match($currentPosition->tile) {
                    self::TOP_LEFT => "left",
                    self::BOTTOM_LEFT => "right",
                    default => "up"
                };
                if($currentPosition->orientation == "left") {
                    $this->leftTurns++;
                }
                if($currentPosition->orientation == "right") {
                    $this->rightTurns++;
                }
                break;
            case "down":
                $currentPosition->orientation = match($currentPosition->tile) {
                    self::TOP_LEFT => "right",
                    self::TOP_RIGHT => "left",
                    default => "down"
                };
                if($currentPosition->orientation == "down") {
                    break;
                }
                if($currentPosition->orientation == "left") {
                    $this->leftTurns++;
                }
                if($currentPosition->orientation == "right") {
                    $this->rightTurns++;
                }
                break;
            case "left":
                $currentPosition->orientation = match($currentPosition->tile) {
                    self::BOTTOM_RIGHT => "left",
                    self::TOP_RIGHT => "right",
                    default => "down"
                };
                if($currentPosition->orientation == "left") {
                    $this->leftTurns++;
                }
                if($currentPosition->orientation == "right") {
                    $this->rightTurns++;
                }
                break;
        }
    }

    public function __toString(): string
    {
        $oldY = 0;
        $result = "";

        foreach($this->coordinateArray as $coord)
        {
            if($oldY !== $coord->y) {
                $result .= "\n";
            }

            $result .= (string) $coord;

            $oldY = $coord->y;
        }

        return $result;
    }
}