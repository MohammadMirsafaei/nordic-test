<?php

namespace App\Services;

use App\DTOs\TransactionDTO;
use App\DTOs\TransactionToCreateDTO;
use App\DTOs\WalletDTO;
use App\Enums\TransactionTypeEnum;
use App\Exceptions\WalletNotFoundException;
use App\Repositories\WalletRepositoryInterface;

class WalletService
{
    public function __construct(
        private WalletRepositoryInterface $walletRepository,
    )
    {
    }


    /**
     * @throws WalletNotFoundException
     */
    public function getWalletById(int $id): WalletDTO
    {
        $wallet = $this->walletRepository->getById($id);

        if (is_null($wallet)) {
            throw new WalletNotFoundException;
        }

        return $wallet;
    }

    public function getWalletByUserId(int $userId): WalletDTO
    {
        $wallet = $this->walletRepository->getByUserId($userId);

        if (is_null($wallet)) {
            throw new WalletNotFoundException;
        }

        return $wallet;
    }

    public function updateBalanceByUserId(int $userId, float $amount): ?TransactionDTO
    {
        return $this->walletRepository->updateBalanceByTransaction(
            new TransactionToCreateDTO(
                $amount > 0 ? $amount : $amount * -1,
                TransactionTypeEnum::fromAmount($amount),
                $userId,
            ),
            $amount
        );
    }
}
