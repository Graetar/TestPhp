<?php

declare(strict_types=1);

namespace Library\Money;

/**
 * Interface IOperations
 * @package Library\Money
 */
interface IOperationsFactory
{

    public function create(): Operations;

    /**
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
     * @throws \InvalidArgumentException
     */
    public function exchangeAmount(float $amount, string $currencyFrom, string $currencyTo, ?int $precision): float;
    
    public function roundAmount(float $amount, ?int $precision): float;
}
