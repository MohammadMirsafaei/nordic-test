<?php

namespace App\Repositories;

use App\DTOs\TransactionDTO;
use App\DTOs\TransactionToCreateDTO;
use App\DTOs\WalletDTO;

interface WalletRepositoryInterface
{
    public function getById(int $id): ?WalletDTO;

    public function getByUserId(int $userId): ?WalletDTO;

    public function updateBalanceByTransaction(TransactionToCreateDTO $transactionDTO, float $amount): ?TransactionDTO;
}
