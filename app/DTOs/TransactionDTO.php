<?php

namespace App\DTOs;

use App\Enums\TransactionTypeEnum;
use Carbon\Carbon;

class TransactionDTO
{
    public function __construct(
        public readonly int $id,
        public readonly float $amount,
        public readonly TransactionTypeEnum $type,
        public readonly string $referenceId,
        public readonly int $walletId,
        public readonly ?Carbon $createdAt,
        public readonly ?Carbon $updatedAt,
    ){}
}
