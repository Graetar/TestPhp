<?php

namespace Library\Money;


/**
 * Interface IExchangeRate
 * @package Library\Money
 */
interface IExchangeRate
{

    /**
     * @param float $amount
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param int|null $precision
     * @return float
     * @throws \InvalidArgumentException
     */
    public function convert(float $amount, string $currencyFrom, string $currencyTo, ?int $precision): float;

    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getRate(string $currencyFrom, string $currencyTo): float;

    /**
     * @param float $amount
     * @param int|null $precision
     * @return float
     */
    public function roundAmount(float $amount, ?int $precision): float;
}