<?php

namespace Tests\Library\Money\Deposit;

use Library\Money\Deposit\Wallet;
use PHPUnit\Framework\TestCase;

/**
 * Class WalletTest
 * @package Tests\Library\Money\Deposit
 */
final class WalletTest extends TestCase
{
    /**
     * @dataProvider getTotalAmountInCurrencyProvider
     */
    public function testGetTotalAmountInCurrency(
        string $outputCurrency,
        ?array $currencies,
        ?int $precision,
        float $expected
    )
    {
        $wallet = $this->getWalletMock();

        $this->assertSame($wallet->getTotalAmountInCurrency($outputCurrency, $currencies, $precision), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $wallet->getTotalAmountInCurrency('XYZ');
    }
    
    public function getTotalAmountInCurrencyProvider(): array
    {
        return [
            ['CZK', [], null, 7858.00],
            ['CZK', ['CZK'], null, 2500.00],
            ['CZK', ['EUR', 'USD'], null, 5358.00],
            ['CZK', ['CZK'], 2500.0],
            ['CZK', ['EUR', 'USD'], 5358],
        ];
    }

    /**
     * @dataProvider getTotalAmountsProvider
     */
    public function testGetTotalAmounts(?array $currencies, float $expected)
    {
        $wallet = $this->getWalletMock();
        $this->assertSame($wallet->getTotalAmounts($currencies), $expected);
    }
    
    public function getTotalAmountsProvider(): array
    {
        return [
            [[], '{"CZK":2500,"EUR":135,"USD":77}'],
            [['CZK'], '{"CZK":2500}'],
            [['EUR', 'USD'], '{"EUR":1350,"USD":77}'],
        ];
    }

    /**
     * @dataProvider getAmountByCurrencyProvider
     */
    public function testGetAmountByCurrency(string $currency, ?int $precision, float $expected)
    {
        $wallet = $this->getWalletMock();
        $this->assertSame($wallet->getAmountByCurrency($currency, $precision), $expected);
    }
    
    public function getAmountByCurrencyProvider(): array
    {
        return [
            ['CZK', null, 2500.00],
            ['EUR', null, 135.00],
            ['XYZ', null, 0.00],
            ['CZK', 0, 2500],
            ['EUR', 1, 135.0],
        ];
    }

    /**
     * @dataProvider addAmountProvider
     */
    public function testAddAmount(float $amount, string $currency, string $addedAmountCurrency, array $expected)
    {
        $wallet = $this->getWalletMock();

        $wallet->addAmount($amount, $currency, $addedAmountCurrency);
        $this->assertSame($wallet->getAmounts(), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $wallet->addAmount(30.00, 'XYZ', 'USD');
        $this->expectException(\InvalidArgumentException::class);
        $wallet->addAmount(30.00, 'EUR', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $wallet->addAmount(30.00, 'XYZ', 'YZX');
    }
    
    public function addAmountProvider(): array
    {
        return [
            [20.00, 'CZK', 'CZK', ['CZK' => 2520.00, 'EUR' => 135.00, 'USD' => 77.00]],
            [10.00, 'EUR', 'CZK', ['CZK' => 2780.00, 'EUR' => 135.00, 'USD' => 77.00]],
            [100.00, 'EUR', 'USD', ['CZK' => 2780.00, 'EUR' => 135.00, 'USD' => 185.00]],
        ];
    }

    /**
     * @dataProvider substractAmountProvider
     */
    public function testSubstractAmount(
        float $amount,
        string $currency,
        string $substractedAmountCurrency,
        array $expected
    )
    {
        $wallet = $this->getWalletMock();

        $wallet->addAmount($amount, $currency, $substractedAmountCurrency);
        $this->assertSame($wallet->getAmounts(), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $wallet->substractAmount(30.00, 'XYZ', 'USD');
        $this->expectException(\InvalidArgumentException::class);
        $wallet->substractAmount(30.00, 'EUR', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $wallet->substractAmount(30.00, 'XYZ', 'YZX');
    }
    
    public function substractAmountProvider(): array
    {
        return [
            [20.00, 'CZK', 'CZK', ['CZK' => 2480.00, 'EUR' => 135.00, 'USD' => 77.00]],
            [10.00, 'EUR', 'CZK', ['CZK' => 2220.00, 'EUR' => 135.00, 'USD' => 77.00]],
            [50.00, 'EUR', 'USD', ['CZK' => 2220.00, 'EUR' => 135.00, 'USD' => 23.00]],
        ];
    }

    /**
     * @dataProvider exchangeAmountProvider
     */
    public function testExchangeAmount(float $amount, string $currencyFrom, string $currencyTo, array $expected)
    {
        $wallet = $this->getWalletMock();

        $wallet->exchangeAmount($amount, $currencyFrom, $currencyTo);
        $this->assertSame($wallet->getAmounts(), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $wallet->exchangeAmount(30.00, 'XYZ', 'USD');
        $this->expectException(\InvalidArgumentException::class);
        $wallet->exchangeAmount(30.00, 'EUR', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $wallet->exchangeAmount(30.00, 'XYZ', 'YZX');
    }
    
    public function exchangeAmountProvider(): array
    {
        return [
            [260.00, 'CZK', 'EUR', ['CZK' => 2240.00, 'EUR' => 145.00, 'USD' => 77.00]],
            [10.00, 'EUR', 'CZK', ['CZK' => 2500.00, 'EUR' => 135.00, 'USD' => 77.00]],
            [50.00, 'EUR', 'USD', ['CZK' => 2500.00, 'EUR' => 85.00, 'USD' => 131.00]],
        ];
    }

    public function testGetDepositName()
    {
        $wallet = $this->getWalletMock();
        $this->assertSame($wallet->getDepositName(), 'WALLET');
    }

    private function getWalletMock()
    {
        $wallet = $this->createMock(Wallet::class);
        $wallet->setAmounts(['CZK' => 2500.00, 'EUR' => 135.00, 'USD' => 77.00]);

        return $wallet;
    }
}
