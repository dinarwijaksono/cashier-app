<?php

namespace App\Repository;

use App\Models\ItemTransaction;
use Illuminate\Support\Facades\Log;

class ItemTransactionRepository
{
    public function create(int $itemId, int $date, string $type, int $qty): void
    {
        try {

            if (in_array($type, ['internal_use', 'receipt'])) {
                $qtyIn = 0;
                $qtyOut = $qty;
            } else {
                $qtyIn = $qty;
                $qtyOut = 0;
            }

            $month = date('n', $date / 1000);
            $year = date('Y', $date / 1000);
            $period = mktime(0, 0, 0, $month, 1, $year);
            $period = $period * 1000;

            ItemTransaction::insert([
                'item_id' => $itemId,
                'period_by_date' => $period,
                'date' => $date,
                'type' => $type,
                'qty_in' => $qtyIn,
                'qty_out' => $qtyOut,
                'created_at' => round(microtime(true) * 1000),
                'updated_at' => round(microtime(true) * 1000),
            ]);

            Log::info('create item transaction success');
        } catch (\Throwable $th) {
            Log::error('create item transaction failed', [
                'message' => $th->getMessage()
            ]);
        }
    }
}
