<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/{year}', 'year_route')]
    public function year(int $year = 2024): Response
    {
        return $this->render('home/year.html.twig', [
            'currentYear' => $year
        ]);
    }

    #[Route('/{year}/day/{day}', 'day_route')]
    public function day(int $year, int $day): Response
    {
        $class = "App\\Services\\Y$year\\Day$day\\Solver";

        if(class_exists($class)) {
            $solver = new $class;
            
            $realVariables = [
                'resultFirstStar' => $solver->firstStar(),
                'resultSecondStar' => $solver->secondStar(),
                'puzzleInput' => $solver->getInput()
            ];

            $testExists = $solver->useTestinput();
            $testVariables = [];

            If($testExists)
            {
                $testVariables = [
                    'resultFirstStar' => $solver->firstStar(),
                    'resultSecondStar' => $solver->secondStar(),
                    'puzzleInput' => $solver->getInput()
                ];
            }

            $variables = [
                'test' => $testVariables,
                'real' => $realVariables
            ];
        } else {
            $variables = [
                'error' => "Please create the file: $class.php it should extend App\\Services\\AbstractSolver.php"
            ];
        }

        return $this->render('home/day.html.twig', [
            'currentYear' => $year,
            'currentDay' => $day,
            ...$variables
        ]);
    }
}
