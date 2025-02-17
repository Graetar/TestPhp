<?php

declare(strict_types=1);

namespace Library\Money\Deposit;

use Library\Money\IOperationsFactory;

/**
 * Interface IDepositFactory
 * @package Library\Money\Deposit
 */
interface IDepositFactory
{

    public function create(): AbstractDeposit;
    
    public function getOperations(): IOperations;
    
    public function setOperations(IOperations $operations): void;

    /**
     * Gets complete amount converted to certain currency.
     * Client may choose just some currencies (i.e. total amount of only USD and EU money. Empty array means all currencies).
     * @throws \InvalidArgumentException
     */
    public function getTotalAmountInCurrency(string $outputCurrency, ?array $currencies, ?int $precision): float;

    /**
     * Gets JSON representation of the complete amount, sorted by currencies.
     * Client may choose just some currencies (i.e. total amount of only USD and EU money. Empty array means all currencies).
     */
    public function getTotalAmounts(?array $currencies): string;
    
    public function getAmountByCurrency(string $currency, ?int $precision): float;

    /**
     * @throws \InvalidArgumentException
     */
    public function addAmount(float $amount, string $currency, string $addedAmountCurrency): void;

    /**
     * @throws \InvalidArgumentException
     */
    public function substractAmount(
        float $amount,
        string $currency,
        string $substractedAmountCurrency
    ): void;

    /**
     * @throws \InvalidArgumentException
     */
    public function exchangeAmount(float $amount, string $currencyFrom, string $currencyTo): void;
    
    public function getDepositName(): string;
}
