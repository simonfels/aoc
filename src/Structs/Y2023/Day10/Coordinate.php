<?php

declare(strict_types=1);

namespace App\Structs\Y2023\Day10;

class Coordinate implements \Stringable {
    public readonly string $key;

    function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly ?string $tile = null,
        public int $distance = -1,
        public string $orientation = "up",
        public readonly ?CoordinateSystem $parent = null
    ) {
        $this->key = "$y|$x";
    }

    public function up(): Coordinate
    {
        return new Coordinate($this->x, $this->y - 1);
    }

    public function down(): Coordinate
    {
        return new Coordinate($this->x, $this->y + 1);
    }

    public function left(): Coordinate
    {
        return new Coordinate($this->x - 1, $this->y);
    }

    public function right(): Coordinate
    {
        return new Coordinate($this->x + 1, $this->y);
    }

    public function setDistance(int $distance): void
    {
        $this->distance = $distance;
    }

    public function __toString(): string
    {
        if($this->distance == -1) {
            return '<span title="'.$this->mouseOverText().'" style="color: red; position: relative"><span style="position: absolute; top: 0; left: 0; z-index: 1; color: white">x</span>█</span>';
        }

        $convertedTile = str_replace(['L', '7', 'J', 'F', '|', '-', '.'],['╚', '╗', '╝', '╔', '║', '═', '#'], $this->tile);

        return "<span title='".$this->mouseOverText()."' style='position: relative; color: #ffffff66'>$convertedTile".$this->coloredArea()."</span>";
    }

    private function mouseOverText(): string
    {
        return "Distance form Start: $this->distance\nOriginal Tile: $this->tile\nOrientation: $this->orientation";
    }

    private function coloredArea(): string
    {
        if($this->parent->leftInside()) {
            $convertedOutside = str_replace(['L', '7', 'J', 'F', '|', '-'],['▙', '▜', '▟', '▛', '▌', '▀'], $this->tile);
            $convertedInside = str_replace(['L', '7', 'J', 'F', '|', '-'],['▝', '▖', '▘', '▗', '▐', '▄'], $this->tile);
        } else {
            $convertedInside = str_replace(['L', '7', 'J', 'F', '|', '-'],['▙', '▜', '▟', '▛', '▌', '▀'], $this->tile);
            $convertedOutside = str_replace(['L', '7', 'J', 'F', '|', '-'],['▝', '▖', '▘', '▗', '▐', '▄'], $this->tile);
        }

        $inside = "<span style='position: absolute; top: 0; left: 0; z-index: -1; color: green'>$convertedInside</span>";
        $outside = "<span style='position: absolute; top: 0; left: 0; z-index: -1; color: red'>$convertedOutside</span>";

        return "$inside$outside";
    }
}
