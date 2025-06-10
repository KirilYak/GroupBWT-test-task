<?php declare(strict_types=1);

namespace App\Components\BinList\Infrastructure;

use App\Components\BinList\Services\APIGatewayInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

final class APIGateway implements APIGatewayInterface
{
    private Client $client;
    public function __construct(string $url, float $timeOut)
    {
        $this->client = new Client([
            'base_uri' => $url,
            'timeout'  => $timeOut,
        ]);
    }

    public function getData(string $cardNumberDigits): Response
    {
        try {
            return $this->client->request('GET', $cardNumberDigits);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}