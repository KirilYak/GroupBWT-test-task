<?php declare(strict_types=1);

namespace App\Components\CommissionsCalculator\ValueObject;

final class EUCountries
{
    private static array $countries = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU',
        'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
    ];
    public static function getCountries(): array
    {
        return self::$countries;
    }
}