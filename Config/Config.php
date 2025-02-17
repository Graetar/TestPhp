<?php

declare(strict_types=1);

namespace Library\Config;

/**
 * Class Config
 * @package Library\Config
 */
class Config
{
    public const EMPTY_AMOUNT = 0.00;

    public const DEFAULT_PRECISION = 2;

    public const EXCHANGE_RATES = [
        'CZK' => [
            'EUR' => 0.040,
            'USD' => 0.042,
        ],
        'EUR' => [
            'CZK' => 25.04,
            'USD' => 1.05,
        ],
        'USD' => [
            'CZK' => 23.87,
            'EUR' => 0.95,
        ],
    ];
}
