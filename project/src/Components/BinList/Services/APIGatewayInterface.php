<?php declare(strict_types=1);

namespace App\Components\BinList\Services;

use GuzzleHttp\Psr7\Response;

interface APIGatewayInterface
{
    public function getData(string $cardNumberDigits): Response;
}