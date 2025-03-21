<?php

declare(strict_types=1);

namespace Library\Money;

use Library\Config\Config;

/**
 * Class ExchangeRate
 * @package Library\Money
 */
final class ExchangeRate implements IExchangeRateFactory
{

    public function create(): ExchangeRate
    {
        return new self;
    }
    
    public function convert(float $amount, string $currencyFrom, string $currencyTo, ?int $precision): float
    {
        if ($currencyFrom === $currencyTo) {
            return $this->roundAmount($amount, $precision);
        }

        return $this->roundAmount($amount * $this->getRate($currencyFrom, $currencyTo), $precision);
    }
    
    public function getRate(string $currencyFrom, string $currencyTo): float
    {
        $exhangeRates = Config::EXCHANGE_RATES;
        if (!isset($exhangeRates[$currencyFrom][$currencyTo])) {
            throw new \InvalidArgumentException('Currency combination does not exist.');
        }

        return $exhangeRates[$currencyFrom][$currencyTo];
    }
    
    public function roundAmount(float $amount, ?int $precision): float
    {
        $precision = $precision ?? Config::DEFAULT_PRECISION;
        return round($amount, intval($precision));
    }
}
