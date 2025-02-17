<?php

declare(strict_types=1);

namespace Tests\Library\Money;

use Library\Money\Operations;
use PHPUnit\Framework\TestCase;

/**
 * Class OperationsTest
 * @package Tests\Library\Money
 */
final class OperationsTest extends TestCase
{

    /**
     * @dataProvider addAmountProvider
     */
    public function testAddAmount(
        float $amount1,
        string $currency1,
        float $amount2,
        string $currency2,
        string $outputCurrency,
        ?int $precision,
        $expected
    )
    {
        $operations = $this->createMock(Operations::class);

        $this->assertSame(
            $operations->addAmount($amount1, $currency1, $amount2, $currency2, $outputCurrency, $precision),
            $expected
        );

        $this->expectException(\InvalidArgumentException::class);
        $operations->addAmount(100.00, 'XYZ', 20.00, 'CZK', 'CZK');
        $this->expectException(\InvalidArgumentException::class);
        $operations->addAmount(100.00, 'CZK', 20.00, 'XYZ', 'CZK');
        $this->expectException(\InvalidArgumentException::class);
        $operations->addAmount(100.00, 'CZK', 20.00, 'CZK', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $operations->addAmount(100.00, 'XYZ', 20.00, 'ZXY', 'YZX');
    }
    
    public function addAmountProvider(): array
    {
        return [
            [100.00, 'CZK', 20.00, 'CZK', 'CZK', null, 120.00],
            [240.00, 'CZK', 50.00, 'EUR', 'USD', null, 64.00],
            [240.00, 'CZK', 10.00, 'USD', 'USD', 1, 20.0],
        ];
    }

    /**
     * @dataProvider substractAmountProvider
     */
    public function testSubstractAmount(
        float $minuendAmount,
        string $minuendCurrency,
        float $subtrahendAmount,
        string $subtrahendCurrency,
        string $outputCurrency,
        ?int $precision,
        $expected
    )
    {
        $operations = $this->createMock(Operations::class);

        $this->assertSame(
            $operations->substractAmount(
                $minuendAmount,
                $minuendCurrency,
                $subtrahendAmount,
                $subtrahendCurrency,
                $outputCurrency,
                $precision
            ),
            $expected
        );

        $this->expectException(\InvalidArgumentException::class);
        $operations->substractAmount(100.00, 'XYZ', 20.00, 'CZK', 'CZK');
        $this->expectException(\InvalidArgumentException::class);
        $operations->substractAmount(100.00, 'CZK', 20.00, 'XYZ', 'CZK');
        $this->expectException(\InvalidArgumentException::class);
        $operations->substractAmount(100.00, 'CZK', 20.00, 'CZK', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $operations->substractAmount(100.00, 'XYZ', 20.00, 'ZXY', 'YZX');
    }
    
    public function substractAmountProvider(): array
    {
        return [
            [100.00, 'CZK', 20.00, 'CZK', 'CZK', null, 80.00],
            [2400.00, 'CZK', 50.00, 'EUR', 'USD', null, 46.00],
            [240.00, 'CZK', 10.00, 'USD', 'USD', 1, 0.0],
        ];
    }

    /**
     * @dataProvider multiplyAmountProvider
     */
    public function testMultiplyAmount(
        float $amount,
        string $currency,
        int $multiplier,
        string $outputCurrency,
        ?int $precision,
        float $expected
    )
    {
        $operations = $this->createMock(Operations::class);

        $this->assertSame(
            $operations->multiplyAmount($amount, $currency, $multiplier, $outputCurrency, $precision),
            $expected
        );

        $this->expectException(\InvalidArgumentException::class);
        $operations->multiplyAmount(100.00, 'XYZ', 5, 'CZK');
        $this->expectException(\InvalidArgumentException::class);
        $operations->multiplyAmount(100.00, 'CZK', 5, 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $operations->multiplyAmount(100.00, 'XYZ', 5, 'YZX');
    }
    
    public function multiplyAmountProvider(): array
    {
        return [
            [100.00, 'CZK', 5, 'CZK', null, 500.00],
            [240.00, 'CZK', 3, 'USD', null, 30.00],
            [2400.00, 'CZK', 5, 'USD', 1, 500.0],
        ];
    }

    /**
     * @dataProvider divideAmountProvider
     */
    public function testDivideAmount(
        float $amount,
        string $currency,
        int $divisor,
        string $outputCurrency,
        ?int $precision,
        array $expected
    )
    {
        $operations = $this->createMock(Operations::class);

        $this->assertSame(
            $operations->divideAmount($amount, $currency, $divisor, $outputCurrency, $precision),
            $expected
        );

        $this->expectException(\InvalidArgumentException::class);
        $operations->divideAmount(100.00, 'XYZ', 5, 'CZK');
        $this->expectException(\InvalidArgumentException::class);
        $operations->divideAmount(100.00, 'CZK', 5, 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $operations->divideAmount(100.00, 'XYZ', 5, 'YZX');
        $this->expectException(\InvalidArgumentException::class);
        $operations->divideAmount(100.00, 'USD', 0, 'EUR');
    }
    
    public function divideAmountProvider(): array
    {
        return [
            [100.00, 'CZK', 2, 'CZK', null, 50.00],
            [720.00, 'CZK', 3, 'USD', null, 10.00],
            [2400.00, 'CZK', 5, 'USD', 1, 20.0],
        ];
    }

    /**
     * @dataProvider compareAmountsProvider
     */
    public function testCompareAmounts(
        float $amount1,
        string $currency1,
        float $amount2,
        string $currency2,
        string $outputCurrency,
        ?int $precision,
        array $expected
    )
    {
        $operations = $this->createMock(Operations::class);

        $this->assertSame(
            $operations->compareAmounts($amount1, $currency1, $amount2, $currency2, $outputCurrency, $precision),
            $expected
        );

        $this->expectException(\InvalidArgumentException::class);
        $operations->compareAmounts(100.00, 'XYZ', 20.00, 'CZK', 'CZK');
        $this->expectException(\InvalidArgumentException::class);
        $operations->compareAmounts(100.00, 'CZK', 20.00, 'XYZ', 'CZK');
        $this->expectException(\InvalidArgumentException::class);
        $operations->compareAmounts(100.00, 'CZK', 20.00, 'CZK', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $operations->compareAmounts(100.00, 'XYZ', 20.00, 'ZXY', 'YZX');
    }
    
    public function compareAmountsProvider(): array
    {
        return [
            [100.00, 'CZK', 20.00, 'CZK', 'CZK', null, 80.00],
            [2400.00, 'CZK', 50.00, 'EUR', 'USD', null, 46.00],
            [240.00, 'CZK', 10.00, 'USD', 'USD', 1, 0.0],
        ];
    }

    /**
     * @dataProvider exchangeAmountProvider
     */
    public function testExchangeAmount(
        float $amount,
        string $currencyFrom,
        string $currencyTo,
        ?int $precision,
        array $expected
    )
    {
        $operations = $this->createMock(Operations::class);

        $this->assertSame($operations->exchangeAmount($amount, $currencyFrom, $currencyTo, $precision), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $operations->exchangeAmount(30.00, 'XYZ', 'USD');
        $this->expectException(\InvalidArgumentException::class);
        $operations->exchangeAmount(30.00, 'USD', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $operations->exchangeAmount(30.00, 'XYZ', 'YZX');
    }
    
    public function exchangeAmountProvider(): array
    {
        return [
            [260.00, 'CZK', 'EUR', null, 10.00],
            [100.00, 'EUR', 'USD', 1, 108.0],
        ];
    }

    /**
     * @dataProvider roundAmountProvider
     */
    public function testRoundAmount(float $amount, ?int $precision, float $expected)
    {
        $operations = $this->createMock(Operations::class);
        $this->assertSame($operations->roundAmount($amount, $precision), $expected);
    }
    
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
