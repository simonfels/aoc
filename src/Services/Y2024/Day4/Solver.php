<?php

declare(strict_types=1);

namespace App\Services\Y2024\Day4;

use App\Services\AbstractSolver;

class Solver extends AbstractSolver {
    public function firstStar(): int
    {
        $horizontals = $this->getInput();
        $verticals = [];
        $diagonals = [];
        $reverseDiagonals = [];
        
        foreach($this->getLines() as $y => $line)
        {
            foreach(str_split( $line) as $x => $character)
            {
                $verticals[$x] ??= "";
                $verticals[$x] .= $character;
                $diagonals[$x + $y] ??= "";
                $diagonals[$x + $y] .= $character;
                $reverseDiagonals[-$y - $x] ??= "";
                $reverseDiagonals[-$y - $x] .= $character;
            }
        }

        $angles = [
            $horizontals,
            implode(PHP_EOL, $verticals),
            implode(PHP_EOL, $diagonals),
            implode(PHP_EOL, $reverseDiagonals)
        ];

        return array_sum(
            array_map(
                function($lines) {
                    preg_match_all('/(?=(XMAS|SAMX))/', $lines, $matches);

                    return count($matches[0]);
                },
                $angles
            )
        );
    }

    public function secondStar(): int
    {
        $diagonals = [];
        $reverseDiagonals = [];
        
        foreach($this->getLines() as $y => $line)
        {
            foreach(str_split( $line) as $x => $character)
            {
                $diagonals[$x - $y] ??= "";
                $diagonals[$x - $y] .= $character;
                $reverseDiagonals[$y + $x] ??= "";
                $reverseDiagonals[$y + $x] .= $character;
            }
        }

        $angles = [$diagonals, $reverseDiagonals];

        foreach($diagonals as $x => $diagonal)
        {
            preg_match_all('/(?=(MAS|SAM))/', $diagonal, $matches, PREG_OFFSET_CAPTURE);

            if($this->isTesting()) {
                print('<pre>');
                foreach($matches[1] as $match)
                {
                    $offset = $match[1];
                    $y = $offset + 1 + $x;
                    print($reverseDiagonals[$x + $y]."\n");
                    if(in_array(substr($reverseDiagonals[$x + $y], $offset, 3), ["SAM", "MAS"])) {
                        print('Yes');
                    }
                }
                print($diagonal."\n");

                /*$y = $matches[1][1][1] + 1 + $x;
                print($matches[1][1][1]."\n");
                print($reverseDiagonals[$x + $y]."\n");
                if(in_array(substr($reverseDiagonals[$x + $y], $matches[1][1][1], 3), ["SAM", "MAS"])) {
                    print('Yes');
                }*/
                print('</pre>');
            }
        }

        return 0;
    }
}

// x - 1 = 1
// 2
// 2+1 = 3
