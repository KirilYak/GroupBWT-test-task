<?php declare(strict_types=1);

namespace App\Components\CommissionsCalculator\Services;

use App\Components\CommissionsCalculator\CommissionsCalculatorServiceInterface;
use App\Components\CommissionsCalculator\Entity\Transaction;
use App\Components\CommissionsCalculator\ValueObject\EUCountries;

final class CommissionsCalculatorService implements CommissionsCalculatorServiceInterface
{
    private $rates = [];

    public function __construct(
        private array $commissionOptions,
        private BinServiceInterface $binService,
        private ExchangeRatesServiceInterface $exchangeRatesService,
    ) {}

    public function calculateCommissionsFromFile(string $pathToFile): void
    {
        $transactions = $this->parseTransactionsFile($pathToFile);
        if (empty($transactions)) {
            return;
        }

        $this->calculateAllTransactionsCommission($transactions);
    }

    private function calculateAllTransactionsCommission(array $transactions): void
    {
        $this->rates = $this->getRates();
        foreach ($transactions as $transaction) {
            dump($this->calculateTransactionCommission($transaction, $this->rates));
        }
    }

    private function calculateTransactionCommission(Transaction $transaction, array $rates): float
    {
        $transactionCurrencyRate = $rates[$transaction->getCurrency()];
        if ($transaction->getCurrency() === $this->commissionOptions['mainCurrency'] || $transactionCurrencyRate === 0) {
            $amount = $transaction->getAmount();
        } else {
            $amount = $transaction->getAmount()/$transactionCurrencyRate;
        }

        $countryCode = $this->binService->getBinByCardDigits($transaction->getBin());

        // TODO: test data for trial requests limit permission
        //$countryCodes = ['DE','US', 'JP', 'US', 'GB'];
        //$countryCode = $countryCodes[rand(0,1)];

        $commission = ($this->isEuCountry($countryCode)) ? $amount * $this->commissionOptions['euCommission']
            : $amount * $this->commissionOptions['nonEuCommission'];

        return round($commission, 2);
    }

    private function getRates(): array
    {
        return $this->exchangeRatesService->getExchangeRates();
    }

    private function isEuCountry(string $countryCode): bool
    {
        return in_array($countryCode, EUCountries::getCountries(), true);
    }

    private function parseTransactionsFile($pathToFile): array
    {
        try {
            $data = file_get_contents($pathToFile);
            $result = [];
            foreach (explode("\n", $data) as $row) {
                $transaction = (array)json_decode($row);
                $result[] = new Transaction($transaction['bin'], (float)$transaction['amount'], $transaction['currency']);
            }

            return $result;
        } catch (\Throwable $exception) {
            throw new \Exception("File parsing error: " . $exception->getMessage());
        }

    }
}