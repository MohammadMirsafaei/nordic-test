<?php

namespace App\DTOs;

use Carbon\Carbon;

class WalletDTO
{
    public function __construct(
        public readonly int $id,
        public readonly float $balance,
        public readonly int $user_id,
        public readonly ?Carbon $createdAt,
        public readonly ?Carbon $updatedAt,
    ){}
}
