<?php

namespace Library\Money;


/**
 * Interface IOperations
 * @package Library\Money
 */
interface IOperations
{

    /**
     * @param float $amount1
     * @param string $currency1
     * @param float $amount2
     * @param string $currency2
     * @param string $outputCurrency
     * @param int|null $precision
     * @return float
     * @throws \InvalidArgumentException
     */
    public function addAmount(
        float $amount1,
        string $currency1,
        float $amount2,
        string $currency2,
        string $outputCurrency,
        ?int $precision
    ): float;

    /**
     * @param float $minuendAmount
     * @param string $minuendCurrency
     * @param float $subtrahendAmount
     * @param string $subtrahendCurrency
     * @param string $outputCurrency
     * @param int|null $precision
     * @return float
     * @throws \InvalidArgumentException
     */
    public function substractAmount(
        float $minuendAmount,
        string $minuendCurrency,
        float $subtrahendAmount,
        string $subtrahendCurrency,
        string $outputCurrency,
        ?int $precision
    ): float;

    /**
     * @param float $amount
     * @param string $currency
     * @param int $multiplier
     * @param string $outputCurrency
     * @param int|null $precision
     * @return float
     * @throws \InvalidArgumentException
     */
    public function multiplyAmount(
        float $amount,
        string $currency,
        int $multiplier,
        string $outputCurrency,
        ?int $precision
    ): float;

    /**
     * @param float $amount
     * @param string $currency
     * @param int $divisor
     * @param string $outputCurrency
     * @param int|null $precision
     * @return float
     * @throws \InvalidArgumentException
     */
    public function divideAmount(
        float $amount,
        string $currency,
        int $divisor,
        string $outputCurrency,
        ?int $precision
    ): float;

    /**
     * @param float $amount1
     * @param string $currency1
     * @param float $amount2
     * @param string $currency2
     * @param string $outputCurrency
     * @param int|null $precision
     * @return float
     * @throws \InvalidArgumentException
     */
    public function compareAmounts(
        float $amount1,
        string $currency1,
        float $amount2,
        string $currency2,
        string $outputCurrency,
        ?int $precision
    ): float;

    /**
     * @param float $amount
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param int|null $precision
     * @return float
     * @throws \InvalidArgumentException
     */
    public function exchangeAmount(float $amount, string $currencyFrom, string $currencyTo, ?int $precision): float;

    /**
     * @param float $amount
     * @param int|null $precision
     * @return float
     */
    public function roundAmount(float $amount, ?int $precision): float;
}