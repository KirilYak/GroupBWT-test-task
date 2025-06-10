<?php declare(strict_types=1);

namespace App\Components\CommissionsCalculator\Infrastructure;

use App\Components\BinList\BinListServiceInterface;
use App\Components\CommissionsCalculator\Services\BinServiceInterface;

final class BinListServiceAdapter implements BinServiceInterface
{
    public function __construct(
        private BinListServiceInterface $binListService
    ) {}

    public function getBinByCardDigits(string $cardNumberDigits): string
    {
        return $this->binListService->getBin($cardNumberDigits);
    }
}