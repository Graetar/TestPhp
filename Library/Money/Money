<?php

declare(strict_types=1);

namespace Library\Money;

use Library\Config\Config;

/**
 * Class Money
 * @package Library\Money
 */
final readonly class Money
{
    public function __construct(public int $minorAmount, public string $currency) {}

    public static function fromMajor(float|string $amount, string $currency, int $scale = 2): self
    {
        return new self((int)round(((float)$amount) * (10 ** $scale)), $currency);
    }
}
