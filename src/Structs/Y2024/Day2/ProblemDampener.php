<?php

declare(strict_types=1);

namespace App\Structs\Y2024\Day2;

class ProblemDampener
{
	public function __construct() {
	}

	public function checkReport(Report $report): bool
	{
		return $this->isSafe($report->levels);
	}

	public function checkVariations(Report $report): bool
	{
		$variations = $this->generateVariations($report);

		return \array_any($variations, fn($report) => $this->isSafe($report));
	}

	private function generateVariations(Report $report): array
	{
		$variations = [];

		foreach($report->levels as $key => $level)
		{
			$levels = $report->levels;
			unset($levels[$key]);
			$variations[] = $levels;
		}

		return $variations;
	}

	private function isSafe(array $levels): bool
	{
		$levels = array_values($levels);
		$abslevels = [];
		foreach($levels as $key => $level) {
			if ($key+1 >= count($levels)) { continue; }
			$abslevels[$key] = $levels[$key+1] - $level;
		}
		if (empty($abslevels)) { return false; }
		return (
			count(array_unique($levels)) == count($levels) &&
			(
				\array_all($abslevels, fn($a) => $a > 0 && $a < 4) || array_all($abslevels, fn($a) => $a < 0 && $a > -4)
			)
		);
	}
}

