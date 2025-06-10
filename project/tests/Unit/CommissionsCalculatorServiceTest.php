<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Components\CommissionsCalculator\Entity\Transaction;
use App\Components\CommissionsCalculator\Services\BinServiceInterface;
use App\Components\CommissionsCalculator\Services\CommissionsCalculatorService;
use App\Components\CommissionsCalculator\Services\ExchangeRatesServiceInterface;
use PHPUnit\Framework\TestCase;

final class CommissionsCalculatorServiceTest extends TestCase
{
    private CommissionsCalculatorService $commissionsCalculatorService;
    private $binListService;
    private $exchangeRatesService;
    private $commissionsOptions;

    protected function setUp(): void
    {
        $this->binListService = $this->createMock(BinServiceInterface::class);
        $this->exchangeRatesService = $this->createMock(ExchangeRatesServiceInterface::class);

        $this->commissionsOptions = [
            'mainCurrency' => 'EUR',
            'euCommission' => 0.01,
            'nonEuCommission' => 0.02,
        ];

        $this->commissionsCalculatorService = new CommissionsCalculatorService(
            $this->commissionsOptions,
            $this->binListService,
            $this->exchangeRatesService
        );
    }

    public function testCalculateTransactionCommissionForEUCountry()
    {
        $calculateTransactionCommission = $this->setPrivateMethod($this->commissionsCalculatorService, 'calculateTransactionCommission');
        $transaction = new Transaction('12345', 100.00, 'EUR');
        $rates = ['EUR' => 1];
        $this->binListService->method('getBinByCardDigits')->willReturn("DE");
        $result = $calculateTransactionCommission->invoke($this->commissionsCalculatorService, $transaction, $rates);
        $this->assertEquals($transaction->getAmount() * $this->commissionsOptions['euCommission'], $result);
    }

    public function testCalculateTransactionCommissionForNonEUCountry()
    {
        $calculateTransactionCommission = $this->setPrivateMethod($this->commissionsCalculatorService, 'calculateTransactionCommission');
        $transaction = new Transaction('12345', 100.00, 'EUR');
        $rates = ['EUR' => 1];
        $this->binListService->method('getBinByCardDigits')->willReturn("US");
        $result = $calculateTransactionCommission->invoke($this->commissionsCalculatorService, $transaction, $rates);
        $this->assertEquals($transaction->getAmount() * $this->commissionsOptions['nonEuCommission'], $result);
    }

    public function testIsEuCountryMethodForEUCountry()
    {
        $isEuCountryMethod = $this->setPrivateMethod($this->commissionsCalculatorService, 'isEuCountry');
        $resultEU = $isEuCountryMethod->invoke($this->commissionsCalculatorService, 'DE');
        $this->assertIsBool($resultEU);
        $this->assertEquals($resultEU, true);

        $resultEU = $isEuCountryMethod->invoke($this->commissionsCalculatorService, 'US');
        $this->assertIsBool($resultEU);
        $this->assertEquals($resultEU, false);
    }

    private function setPrivateMethod(object $object, string $method): object
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }
}