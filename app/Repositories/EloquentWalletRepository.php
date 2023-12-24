<?php

namespace App\Repositories;

use App\DTOs\TransactionDTO;
use App\DTOs\TransactionToCreateDTO;
use App\DTOs\WalletDTO;
use App\Enums\TransactionTypeEnum;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class EloquentWalletRepository implements WalletRepositoryInterface
{
    public function getById(int $id): ?WalletDTO
    {
        $wallet = Wallet::find($id);
        if (is_null($wallet)) {
            return $wallet;
        }

        return new WalletDTO(
            $wallet->id,
            $wallet->balance,
            $wallet->user_id,
            $wallet->createdAt,
            $wallet->updatedAt,
        );
    }

    public function getByUserId(int $userId): ?WalletDTO
    {
        $wallet = Wallet::where('user_id', $userId)->first();
        if (is_null($wallet)) {
            return $wallet;
        }

        return new WalletDTO(
            $wallet->id,
            $wallet->balance,
            $wallet->user_id,
            $wallet->createdAt,
            $wallet->updatedAt,
        );
    }

    public function updateBalanceByTransaction(TransactionToCreateDTO $transactionDTO, float $amount): ?TransactionDTO
    {
        return DB::transaction(function() use ($transactionDTO, $amount) {
            $wallet = Wallet::lockForUpdate()->where('user_id', $transactionDTO->userId)->first();

            if (!is_null($wallet)) {
                $transaction = new Transaction([
                    'amount' => $transactionDTO->amount,
                    'type' => $transactionDTO->type,
                ]);

                $wallet->transactions()->save($transaction);

                $wallet->update([
                    'balance' => $wallet->balance + $amount,
                ]);

                return new TransactionDTO(
                    $transaction->id,
                    $transaction->amount,
                    $transaction->type,
                    $transaction->reference_id,
                    $transaction->wallet_id,
                    $transaction->created_at,
                    $transaction->updated_at,
                );
            }
            return null;
        });
    }


}
