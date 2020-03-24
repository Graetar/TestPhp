<?php

namespace Library\Money\Deposit;

use Library\Money\IOperations;


/**
 * Interface IDeposit
 * @package Library\Money\Deposit
 */
interface IDeposit
{

    /**
     * @return IOperations
     */
    public function getOperations(): IOperations;

    /**
     * @param IOperations $operations
     */
    public function setOperations(IOperations $operations): void;

    /**
     * Gets complete amount converted to certain currency.
     * Client may choose just some currencies (i.e. total amount of only USD and EU money. Empty array means all currencies).
     * @param string $outputCurrency
     * @param array $currencies
     * @param int|null $precision
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getTotalAmountInCurrency(string $outputCurrency, ?array $currencies, ?int $precision): float;

    /**
     * Gets JSON representation of the complete amount, sorted by currencies.
     * Client may choose just some currencies (i.e. total amount of only USD and EU money. Empty array means all currencies).
     * @param array $currencies
     * @return string
     */
    public function getTotalAmounts(?array $currencies): string;

    /**
     * @param string $currency
     * @param int|null $precision
     * @return float
     */
    public function getAmountByCurrency(string $currency, ?int $precision): float;

    /**
     * @param float $amount
     * @param string $currency
     * @param string $addedAmountCurrency
     * @throws \InvalidArgumentException
     */
    public function addAmount(float $amount, string $currency, string $addedAmountCurrency): void;

    /**
     * @param float $amount
     * @param string $currency
     * @param string $substractedAmountCurrency
     * @throws \InvalidArgumentException
     */
    public function substractAmount(
        float $amount,
        string $currency,
        string $substractedAmountCurrency
    ): void;

    /**
     * @param float $amount
     * @param string $currencyFrom
     * @param string $currencyTo
     * @throws \InvalidArgumentException
     */
    public function exchangeAmount(float $amount, string $currencyFrom, string $currencyTo): void;

    /**
     * @return string
     */
    public function getDepositName(): string;
}