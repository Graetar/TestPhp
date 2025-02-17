<?php

namespace Tests\Library\Money\Deposit;

use Library\Money\Deposit\Bank;
use PHPUnit\Framework\TestCase;

/**
 * Class BankTest
 * @package Tests\Library\Money\Deposit
 */
final class BankTest extends TestCase
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
        $bank = $this->getBankMock();

        $this->assertSame($bank->getTotalAmountInCurrency($outputCurrency, $currencies, $precision), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $bank->getTotalAmountInCurrency('XYZ');
    }

    /**
     * @return array
     */
    public function getTotalAmountInCurrencyProvider(): array
    {
        return [
            ['CZK', [], null, 78580000.00],
            ['CZK', ['CZK'], null, 25000000.00],
            ['CZK', ['EUR', 'USD'], null, 53580000.00],
            ['CZK', ['CZK'], 25000000.0],
            ['CZK', ['EUR', 'USD'], 53580000],
        ];
    }

    /**
     * @dataProvider getTotalAmountsProvider
     */
    public function testGetTotalAmounts(?array $currencies, float $expected)
    {
        $bank = $this->getBankMock();
        $this->assertSame($bank->getTotalAmounts($currencies), $expected);
    }
    
    public function getTotalAmountsProvider(): array
    {
        return [
            [[], '{"CZK":25000000,"EUR":1350000,"USD":770000}'],
            [['CZK'], '{"CZK":25000000}'],
            [['EUR', 'USD'], '{"EUR":1350000,"USD":770000}'],
        ];
    }

    /**
     * @dataProvider getAmountByCurrencyProvider
     */
    public function testGetAmountByCurrency(string $currency, ?int $precision, float $expected)
    {
        $bank = $this->getBankMock();
        $this->assertSame($bank->getAmountByCurrency($currency, $precision), $expected);
    }
    
    public function getAmountByCurrencyProvider(): array
    {
        return [
            ['CZK', null, 25000000.00],
            ['EUR', null, 1350000.00],
            ['XYZ', null, 0.00],
            ['CZK', 0, 25000000],
            ['EUR', 1, 1350000.0],
        ];
    }

    /**
     * @dataProvider addAmountProvider
     */
    public function testAddAmount(float $amount, string $currency, string $addedAmountCurrency, array $expected)
    {
        $bank = $this->getBankMock();

        $bank->addAmount($amount, $currency, $addedAmountCurrency);
        $this->assertSame($bank->getAmounts(), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $bank->addAmount(30000.00, 'XYZ', 'USD');
        $this->expectException(\InvalidArgumentException::class);
        $bank->addAmount(30000.00, 'EUR', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $bank->addAmount(30000.00, 'XYZ', 'YZX');
    }
    
    public function addAmountProvider(): array
    {
        return [
            [20000.00, 'CZK', 'CZK', ['CZK' => 25020000.00, 'EUR' => 1350000.00, 'USD' => 770000.00]],
            [1000.00, 'EUR', 'CZK', ['CZK' => 25046000.00, 'EUR' => 1350000.00, 'USD' => 770000.00]],
            [2000.00, 'EUR', 'USD', ['CZK' => 25046000.00, 'EUR' => 1350000.00, 'USD' => 772160.00]],
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
        $bank = $this->getBankMock();

        $bank->addAmount($amount, $currency, $substractedAmountCurrency);
        $this->assertSame($bank->getAmounts(), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $bank->substractAmount(30000.00, 'XYZ', 'USD');
        $this->expectException(\InvalidArgumentException::class);
        $bank->substractAmount(30000.00, 'EUR', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $bank->substractAmount(30000.00, 'XYZ', 'YZX');
    }
    
    public function substractAmountProvider(): array
    {
        return [
            [20000.00, 'CZK', 'CZK', ['CZK' => 24980000.00, 'EUR' => 1350000.00, 'USD' => 770000.00]],
            [1000.00, 'EUR', 'CZK', ['CZK' => 24954000.00, 'EUR' => 1350000.00, 'USD' => 770000.00]],
            [2000.00, 'EUR', 'USD', ['CZK' => 25046000.00, 'EUR' => 1350000.00, 'USD' => 767840.00]],
        ];
    }

    /**
     * @dataProvider exchangeAmountProvider
     */
    public function testExchangeAmount(float $amount, string $currencyFrom, string $currencyTo, array $expected)
    {
        $bank = $this->getBankMock();

        $bank->exchangeAmount($amount, $currencyFrom, $currencyTo);
        $this->assertSame($bank->getAmounts(), $expected);

        $this->expectException(\InvalidArgumentException::class);
        $bank->exchangeAmount(30000.00, 'XYZ', 'USD');
        $this->expectException(\InvalidArgumentException::class);
        $bank->exchangeAmount(30000.00, 'EUR', 'XYZ');
        $this->expectException(\InvalidArgumentException::class);
        $bank->exchangeAmount(30000.00, 'XYZ', 'YZX');
    }
    
    public function exchangeAmountProvider(): array
    {
        return [
            [26000.00, 'CZK', 'EUR', ['CZK' => 24974000.00, 'EUR' => 1351000.00, 'USD' => 770000.00]],
            [1000.00, 'EUR', 'CZK', ['CZK' => 25000000.00, 'EUR' => 1350000.00, 'USD' => 770000.00]],
            [2000.00, 'EUR', 'USD', ['CZK' => 25000000.00, 'EUR' => 1348000.00, 'USD' => 774320.00]],
        ];
    }

    public function testGetDepositName()
    {
        $bank = $this->getBankMock();
        $this->assertSame($bank->getDepositName(), 'BANK');
    }

    private function getBankMock()
    {
        $bank = $this->createMock(Bank::class);
        $bank->setAmounts(['CZK' => 25000000.00, 'EUR' => 1350000.00, 'USD' => 770000.00]);

        return $bank;
    }
}
