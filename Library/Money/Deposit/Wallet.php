<?php

namespace Library\Money\Deposit;


/**
 * Class Wallet
 * @package Library\Money\Deposit
 */
final class Wallet extends AbstractDeposit
{

    /**
     * @inheritDoc
     */
    public function getDepositName(): string
    {
        return 'WALLET';
    }
}