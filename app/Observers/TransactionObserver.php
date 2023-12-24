<?php

namespace App\Observers;

use App\Enums\TransactionTypeEnum;
use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $prefix = "";
        switch ($transaction->type) {
            case TransactionTypeEnum::DEBIT:
                $prefix = 'DEB';
                break;
            case TransactionTypeEnum::CREDIT:
                $prefix = 'CRED';
                break;
        }
        $transaction->reference_id = "{$prefix}{$transaction->created_at->format('YmdHisv')}";
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
