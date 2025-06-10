<?php declare(strict_types=1);

namespace App\Components\CommissionsCalculator;

interface CommissionsCalculatorServiceInterface
{
    public function calculateCommissionsFromFile(string $pathToFile): void;
}