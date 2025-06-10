<?php declare(strict_types=1);

namespace App\Components\BinList\Services;

use App\Components\BinList\BinListServiceInterface;

final class BinListService implements BinListServiceInterface
{
    public function __construct(public APIGatewayInterface $apiGateway)
    {
    }

    public function getBin(string $cardNumberDigits): string
    {
        $jsonResponse = $this->apiGateway->getData($cardNumberDigits);

        return json_decode($jsonResponse->getBody()->getContents())->country->alpha2;
    }
}