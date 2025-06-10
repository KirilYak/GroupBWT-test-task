<?php declare(strict_types=1);

namespace App\Components\CommissionsCalculator\Infrastructure;

use App\Components\CommissionsCalculator\Services\ExchangeRatesServiceInterface;
use App\Components\ExchangeRatesApi\ExchangeRatesApiServiceInterface;

final class ExchangeRatesApiAdapter implements ExchangeRatesServiceInterface
{
    public function __construct(
        private ExchangeRatesApiServiceInterface $exchangeRatesApiService
    ) {}

    public function getExchangeRates(): array
    {
        return $this->exchangeRatesApiService->getExchangeRates();
    }
}