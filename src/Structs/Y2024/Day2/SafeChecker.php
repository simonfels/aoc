<?php

declare(strict_types=1);

namespace App\Structs\Y2024\Day2;

class SafeChecker
{
	private array $reports;
	private ProblemDampener $problemDampener;

	public function __construct(array $inputLines)
	{
		$this->reports = [];
		$this->problemDampener = new ProblemDampener();
		
		foreach($inputLines as $inputLine)
		{
			$this->reports[] = new Report($inputLine);
		}
	}


	public function checkReports(): int
	{
		$sum = 0;
		foreach($this->reports as $report)
		{
			if($this->problemDampener->checkReport($report))
			{
				$sum++;
			}
		}
		return $sum;
	}

	public function checkVariations(): int
	{
		$sum = 0;
		foreach($this->reports as $report)
		{
			if($this->problemDampener->checkVariations($report))
			{
				$sum++;
			}
		}
		return $sum;
	}
}

