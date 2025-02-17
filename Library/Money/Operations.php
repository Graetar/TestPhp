<?php

declare(strict_types=1);

namespace Library\Money;

/**
 * Class Operations
 * @package Library\Money
 */
final class Operations implements IOperations
{

    protected IExchangeRate $exchangeRate;

    public function __construct()
    {
        $this->exchangeRate = new ExchangeRate();
    }
    
    public function addAmount(
        float $amount1,
        string $currency1,
        float $amount2,
        string $currency2,
        string $outputCurrency,
        ?int $precision
    ): float
    {
        $convertedAmount1 = $this->exchangeRate->convert($amount1, $currency1, $outputCurrency, $precision);
        $convertedAmount2 = $this->exchangeRate->convert($amount2, $currency2, $outputCurrency, $precision);

        return $convertedAmount1 + $convertedAmount2;
    }
    
    public function substractAmount(
        float $minuendAmount,
        string $minuendCurrency,
        float $subtrahendAmount,
        string $subtrahendCurrency,
        string $outputCurrency,
        ?int $precision
    ): float
    {
        $convertedMinuendAmount = $this->exchangeRate->convert($minuendAmount, $minuendCurrency, $outputCurrency, $precision);        
        $convertedSubtrahendAmount = $this->exchangeRate->convert($subtrahendAmount, $subtrahendCurrency, $outputCurrency, $precision);

        return $convertedMinuendAmount - $convertedSubtrahendAmount;
    }
    
    public function multiplyAmount(
        float $amount,
        string $currency,
        int $multiplier,
        string $outputCurrency,
        ?int $precision
    ): float
    {
        return $this->exchangeRate->convert(($amount * $multiplier), $currency, $outputCurrency, $precision);
    }
    
    public function divideAmount(
        float $amount,
        string $currency,
        int $divisor,
        string $outputCurrency,
        ?int $precision
    ): float
    {
        if ($divisor === 0) {
            throw new \InvalidArgumentException('Divisor cannot equal 0');
        }

        return $this->exchangeRate->convert(($amount / $divisor), $currency, $outputCurrency, $precision);
    }
    
    public function compareAmounts(
        float $amount1,
        string $currency1,
        float $amount2,
        string $currency2,
        string $outputCurrency,
        ?int $precision
    ): float
    {
        return $this->substractAmount($amount1, $currency1, $amount2, $currency2, $outputCurrency, $precision);
    }

    public function exchangeAmount(float $amount, string $currencyFrom, string $currencyTo, ?int $precision): float
    {
        return $this->exchangeRate->convert($amount, $currencyFrom, $currencyTo, $precision);
    }
    
    public function roundAmount(float $amount, ?int $precision): float
    {
        return $this->exchangeRate->roundAmount($amount, intval($precision));
    }
}
