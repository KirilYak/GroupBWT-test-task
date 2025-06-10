<?php declare(strict_types=1);

namespace App\Components\ExchangeRatesApi\Services;

use GuzzleHttp\Psr7\Response;

interface APIGatewayInterface
{
    public function getRates(): Response;
}