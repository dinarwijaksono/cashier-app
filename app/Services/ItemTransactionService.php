<?php

namespace App\Services;

use App\Models\ItemTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ItemTransactionService
{
    public function boot()
    {
        Log::withContext(['class' => ItemTransactionService::class]);
    }

    // read
    public function getTransactions(): Collection
    {
        try {
            self::boot();

            $transaction = ItemTransaction::select(
                'id',
                'item_id',
                'period_by_date',
                'date',
                'type',
                'qty_in',
                'qty_out',
                'created_at',
                'updated_at'
            )->get();

            Log::info('get transaction success');

            return collect($transaction);
        } catch (\Throwable $th) {

            Log::error('get transaction failed', [
                'message' => $th->getMessage()
            ]);

            return collect([]);
        }
    }
}
