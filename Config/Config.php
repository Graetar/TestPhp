<?php

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
            'EUR' => 0.038,
            'USD' => 0.042,
        ],
        'EUR' => [
            'CZK' => 26.00,
            'USD' => 1.08,
        ],
        'USD' => [
            'CZK' => 24.00,
            'EUR' => 0.92,
        ],
    ];
}