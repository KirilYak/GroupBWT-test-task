<?php declare(strict_types=1);

namespace App\Components\CommissionsCalculator\Services;

interface BinServiceInterface
{
    public function getBinByCardDigits(string $cardNumberDigits): string;
}