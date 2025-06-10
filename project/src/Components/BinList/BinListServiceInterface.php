<?php declare(strict_types=1);

namespace App\Components\BinList;

interface BinListServiceInterface
{
    public function getBin(string $cardNumberDigits): string;
}