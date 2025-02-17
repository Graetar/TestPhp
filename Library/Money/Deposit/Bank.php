<?php

declare(strict_types=1);

namespace Library\Money\Deposit;

/**
 * Class Bank
 * @package Library\Money\Deposit
 */
final class Bank extends AbstractDeposit
{

    /**
     * @inheritDoc
     */
    public function getDepositName(): string
    {
        return 'BANK';
    }
}
