<?php

namespace App\DTOs;

use App\Enums\TransactionTypeEnum;
use Carbon\Carbon;

class TransactionToCreateDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly TransactionTypeEnum $type,
        public readonly int $userId,
    ){}
}
