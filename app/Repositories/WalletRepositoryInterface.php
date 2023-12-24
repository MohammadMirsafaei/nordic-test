<?php

namespace App\Repositories;

use App\DTOs\WalletDTO;

interface WalletRepositoryInterface
{
    public function getById(int $id): ?WalletDTO;

    public function getByUserId(int $userId): ?WalletDTO;

    public function updateBalanceById(int $id, float $amount): void;
}
