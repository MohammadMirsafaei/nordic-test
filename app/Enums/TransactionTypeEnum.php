<?php

namespace App\Enums;

use App\Exceptions\InvalidTransactionAmountValue;

enum TransactionTypeEnum: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';

    public static function fromAmount(float $amount): ?self
    {
        if ($amount == 0) {
            throw new InvalidTransactionAmountValue;
        }
        return $amount > 0 ? self::CREDIT : self::DEBIT;
    }
}
