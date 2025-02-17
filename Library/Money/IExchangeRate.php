<?php

declare(strict_types=1);

namespace Library\Money;

/**
 * Interface IExchangeRateFactory
 * @package Library\Money
 */
interface IExchangeRateFactory
{

    public function create(): ExchangeRate;

    /**
     * @throws \InvalidArgumentException
     */
    public function convert(float $amount, string $currencyFrom, string $currencyTo, ?int $precision): float;

    /**
     * @throws \InvalidArgumentException
     */
    public function getRate(string $currencyFrom, string $currencyTo): float;
    
    public function roundAmount(float $amount, ?int $precision): float;
}
