<?php declare(strict_types=1);

namespace App\Commands;

use App\Components\CommissionsCalculator\CommissionsCalculatorServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'calculate-commissions')]
final class CalculateCommissionsCommand extends Command
{
    private string $fileName = 'input.txt';

   public function __construct(
       private CommissionsCalculatorServiceInterface $commissionsCalculatorService
   ) {
       parent::__construct();
   }

    public function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output): int
    {
        $this->commissionsCalculatorService->calculateCommissionsFromFile($this->buildFilePath());

        return Command::SUCCESS;
    }

    private function buildFilePath(): string
    {
        return dirname(__DIR__, 2).'/storage/' . $this->fileName;
    }
}