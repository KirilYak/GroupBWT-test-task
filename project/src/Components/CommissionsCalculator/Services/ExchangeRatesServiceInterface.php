<?php declare(strict_types=1);

namespace App\Components\CommissionsCalculator\Services;

interface ExchangeRatesServiceInterface
{
    public function getExchangeRates(): array;
}