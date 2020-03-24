<?php

namespace Tests\Library\Money;

use Library\Money\ExchangeRate;
use PHPUnit\Framework\TestCase;


/**
 * Class ExchangeRateTest
 * @package Tests\Library\Money
 */
final class ExchangeRateTest extends TestCase
{
    public function testConvert(
        float $amount,
        string $currencyFrom,
        string $currencyTo,
        ?int $precision,
        float $expected
    ) {
        $exhangeRate = $this->createMock(ExchangeRate::class);

        $this->assertSame($exhangeRate->convert($amount, $currencyFrom, $currencyTo, $precision), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $exhangeRate->getRate(30.00, 'XYZ', 'USD');
        $this->expectException(\InvalidArgumentException::class);
        $exhangeRate->getRate(30.00, 'USD', 'XYZ', 1);
        $this->expectException(\InvalidArgumentException::class);
        $exhangeRate->getRate(30.00, 'XYZ', 'YZX');
    }

    /**
     * @return array
     */
    public function convertProvider(): array
    {
        return [
            [1, 'EUR', 'CZK', 2, 26.00],
            [52, 'CZK', 'EUR', 1, 2.0],
            [48, 'CZK', 'USD', null, 2.00],
        ];
    }

    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param float $expected
     *
     * @dataProvider getRateProvider
     */
    public function testGetRate(string $currencyFrom, string $currencyTo, float $expected)
    {
        $exhangeRate = $this->createMock(ExchangeRate::class);

        $this->assertSame($exhangeRate->getRate($currencyFrom, $currencyTo), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $exhangeRate->getRate('XYZ', 'USD');
        $this->expectException(\InvalidArgumentException::class);
        $exhangeRate->getRate('USD', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $exhangeRate->getRate('XYZ', 'YZX');
    }

    /**
     * @return array
     */
    public function getRateProvider(): array
    {
        return [
            ['EUR', 'CZK', 26.00],
            ['EUR', 'USD', 1.08],
        ];
    }

    /**
     * @param float $amount
     * @param int|null $precision
     * @param float $expected
     *
     * @dataProvider roundAmountProvider
     */
    public function testRoundAmount(float $amount, ?int $precision, float $expected)
    {
        $exhangeRate = $this->createMock(ExchangeRate::class);
        $this->assertSame($exhangeRate->roundAmount($amount, $precision), $expected);
    }

    /**
     * @return array
     */
    public function roundAmountProvider(): array
    {
        return [
            [7858.00, null, 7858.00],
            [7858.00, 1, 7858.0],
            [7858.30, 1, 7858.3],
            [7858.00, 3, 7858.000],
        ];
    }
}