<?php declare(strict_types=1);

namespace App\Components\ExchangeRatesApi\Services;

use App\Components\ExchangeRatesApi\ExchangeRatesApiServiceInterface;
use GuzzleHttp\Psr7\Response;

final class ExchangeRatesApiService implements ExchangeRatesApiServiceInterface
{
    public function __construct(
        private APIGatewayInterface $apiGateway
    ){}

    public function getExchangeRates(): array
    {
        try {
            /** @var Response $result */
            $jsonResult = $this->apiGateway->getRates();
        } catch (\Throwable $exception) {
            dump($exception->getMessage());
        }

        return (array)json_decode($jsonResult->getBody()->getContents())->rates;
    }
}