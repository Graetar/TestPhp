<?php

declare(strict_types=1);

namespace Library\Money\Deposit;

use Library\Config\Config;
use Library\Money\IOperations;
use Library\Money\Operations;

/**
 * Class AbstractDeposit
 * @package Library\Money\Deposit
 */
abstract class AbstractDeposit implements IDeposit
{

    /** @var array */
    protected $amounts = [];

    /** @var IOperations */
    protected $operations;

    public function __construct()
    {
        $this->operations = new Operations();
    }

    /**
     * @return array
     */
    public function getAmounts(): array
    {
        return $this->amounts;
    }

    /**
     * @param array $amounts
     */
    public function setAmounts(array $amounts): void
    {
        $this->amounts = $amounts;
    }

    /**
     * @inheritDoc
     */
    public function getOperations(): IOperations
    {
        return $this->operations;
    }

    /**
     * @inheritDoc
     */
    public function setOperations(IOperations $operations): void
    {
        $this->operations = $operations;
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmountInCurrency(string $outputCurrency, ?array $currencies, ?int $precision): float
    {
        $totalAmount = Config::EMPTY_AMOUNT;
        foreach ($this->getTotalAmountsByCurrencies($currencies, $precision) as $currency => $amount) {
            $totalAmount = $this->operations->addAmount(
                $totalAmount,
                $outputCurrency,
                $amount,
                $currency,
                $outputCurrency,
                $precision
            );
        }

        return $totalAmount;
    }

    /**
     * @inheritDoc
     */
    public function getTotalAmounts(?array $currencies): string
    {
        return json_encode($this->getTotalAmountsByCurrencies($currencies, null));
    }

    /**
     * @inheritDoc
     */
    protected function getTotalAmountsByCurrencies(?array $currencies, ?int $precision): array
    {
        if (empty($currencies)) {
            return $this->getAmounts();
        }

        $amounts = $this->getAmounts();
        foreach ($amounts as $currency => $amount) {
            if (!array_key_exists($currency, $currencies)) {
                unset($amounts[$currency]);
            }
        }

        return $amounts;
    }

    /**
     * @inheritDoc
     */
    public function getAmountByCurrency(string $currency, ?int $precision): float
    {
        return $this->operations->roundAmount($this->amounts[$currency] ?? Config::EMPTY_AMOUNT, $precision);
    }

    /**
     * @inheritDoc
     */
    public function addAmount(float $amount, string $currency, string $addedAmountCurrency): void
    {
        $this->amounts[$addedAmountCurrency] = $this->operations->addAmount(
            $this->getAmountByCurrency($addedAmountCurrency, null),
            $addedAmountCurrency,
            $amount,
            $currency,
            $addedAmountCurrency,
            null
        );
    }

    /**
     * @inheritDoc
     */
    public function substractAmount(float $amount, string $currency, string $substractedAmountCurrency): void
    {
        $this->amounts[$substractedAmountCurrency] = $this->operations->substractAmount(
            $this->getAmountByCurrency($substractedAmountCurrency, null),
            $substractedAmountCurrency,
            $amount,
            $currency,
            $substractedAmountCurrency,
            null
        );
    }

    /**
     * @inheritDoc
     */
    public function exchangeAmount(float $amount, string $currencyFrom, string $currencyTo): void
    {
        $convertedAmount = $this->operations->exchangeAmount($amount, $currencyFrom, $currencyTo, null);
        $this->substractAmount($amount, $currencyFrom, $currencyFrom);
        $this->addAmount($convertedAmount, $currencyTo, $currencyTo);
    }

    /**
     * @inheritDoc
     */
    abstract public function getDepositName(): string;
}
