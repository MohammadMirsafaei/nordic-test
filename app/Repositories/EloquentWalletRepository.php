<?php

namespace App\Repositories;

use App\DTOs\WalletDTO;
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

    public function updateBalanceById(int $id, float $amount): void
    {
        DB::transaction(function() use ($id, $amount) {
            $wallet = Wallet::lockForUpdate()->find($id);
            if (!is_null($wallet)) {
                $wallet->update([
                    'balance' => $wallet->balance + $amount,
                ]);
            }
        });
    }


}
