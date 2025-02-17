<?php

declare(strict_types=1);

namespace Library\Money\Deposit;

use Library\Config\Config;
use Library\Money\IOperationsFactory;
use Library\Money\Operations;

/**
 * Class AbstractDeposit
 * @package Library\Money\Deposit
 */
abstract class AbstractDeposit implements IDepositFactory
{

    protected $amounts = [];

    /** @var Operations */
    protected $operations;

    public function __construct()
    {
        $this->operations = new Operations();
    }

    public function create(): AbstractDeposit
    {
        return new static;
    }
    
    public function getAmounts(): array
    {
        return $this->amounts;
    }
    
    public function setAmounts(array $amounts): void
    {
        $this->amounts = $amounts;
    }
    
    public function getOperations(): Operations
    {
        return $this->operations;
    }
    
    public function setOperations(Operations $operations): void
    {
        $this->operations = $operations;
    }
    
    public function getTotalAmountInCurrency(string $outputCurrency, ?array $currencies, ?int $precision): float
    {
        $totalAmount = Config::EMPTY_AMOUNT;
        foreach ($this->getTotalAmountsByCurrencies($currencies, $precision) as $currency => $amount) {
            $totalAmount = $this->operations->addAmount(
                $totalAmount,
                $outputCurrency,
                $amount,
                $currency,
                $outputCurrency,
                $precision
            );
        }

        return $totalAmount;
    }
    
    public function getTotalAmounts(?array $currencies): string
    {
        return json_encode($this->getTotalAmountsByCurrencies($currencies, null));
    }
    
    protected function getTotalAmountsByCurrencies(?array $currencies, ?int $precision): array
    {
        if (empty($currencies)) {
            return $this->getAmounts();
        }

        $amounts = $this->getAmounts();
        foreach ($amounts as $currency => $amount) {
            if (!array_key_exists($currency, $currencies)) {
                unset($amounts[$currency]);
            }
        }

        return $amounts;
    }
    
    public function getAmountByCurrency(string $currency, ?int $precision): float
    {
        return $this->operations->roundAmount($this->amounts[$currency] ?? Config::EMPTY_AMOUNT, $precision);
    }
    
    public function addAmount(float $amount, string $currency, string $addedAmountCurrency): void
    {
        $this->amounts[$addedAmountCurrency] = $this->operations->addAmount(
            $this->getAmountByCurrency($addedAmountCurrency, null),
            $addedAmountCurrency,
            $amount,
            $currency,
            $addedAmountCurrency,
            null
        );
    }
    
    public function substractAmount(float $amount, string $currency, string $substractedAmountCurrency): void
    {
        $this->amounts[$substractedAmountCurrency] = $this->operations->substractAmount(
            $this->getAmountByCurrency($substractedAmountCurrency, null),
            $substractedAmountCurrency,
            $amount,
            $currency,
            $substractedAmountCurrency,
            null
        );
    }
    
    public function exchangeAmount(float $amount, string $currencyFrom, string $currencyTo): void
    {
        $convertedAmount = $this->operations->exchangeAmount($amount, $currencyFrom, $currencyTo, null);
        $this->substractAmount($amount, $currencyFrom, $currencyFrom);
        $this->addAmount($convertedAmount, $currencyTo, $currencyTo);
    }
    
    abstract public function getDepositName(): string;
}
