<?php

declare(strict_types=1);

namespace Library\Money\Deposit;

/**
 * Class Wallet
 * @package Library\Money\Deposit
 */
final class Wallet extends AbstractDeposit
{
    
    public function getDepositName(): string
    {
        return 'WALLET';
    }
}
