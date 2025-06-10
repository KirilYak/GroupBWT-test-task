<?php declare(strict_types=1);

namespace App\Components\ExchangeRatesApi\Infrastructure;
use App\Components\ExchangeRatesApi\Services\APIGatewayInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

final class APIGateway implements APIGatewayInterface
{
    private Client $client;
    private string $accessKey;
    public function __construct(string $url, float $timeout, string $accessKey)
    {
        $this->accessKey = $accessKey;
        $this->client = new Client([
            'base_uri' => $url,
            'timeout'  => $timeout,
        ]);
    }

    public function getRates(): Response
    {
        try {
            return $this->client->request('GET', '', [
                'query' => [
                    'access_key' => $this->accessKey,
                ]
            ]);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}