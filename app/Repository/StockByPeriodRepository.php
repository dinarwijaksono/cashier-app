<?php

namespace App\Repository;

use App\Models\StockByPeriod;
use Illuminate\Support\Facades\Log;

class StockByPeriodRepository
{
    public function boot(): void
    {
        Log::withContext(['class' => StockByPeriodRepository::class]);
    }

    public function addStock(int $itemId, int $date, int $value): void
    {
        try {
            self::boot();

            $month = date('n', $date / 1000);
            $year = date('Y', $date / 1000);
            $period = mktime(0, 0, 0, $month, 1, $year);
            $period = $period * 1000;

            $stockByPeriod = StockByPeriod::select('item_id', 'last_stock')
                ->where('item_id', $itemId)
                ->where('period_by_date', $period)
                ->orderByDesc('period_by_date')
                ->first();

            if (collect($stockByPeriod)->isEmpty()) {

                StockByPeriod::insert([
                    'item_id' => $itemId,
                    'period' => date('F-Y', $date / 1000),
                    'period_by_date' => $period,
                    'is_closed' => 0,
                    'first_stock' => 0,
                    'adjusment' => 0,
                    'last_stock' => $value,
                    'created_at' => round(microtime(true) * 1000),
                    'updated_at' => round(microtime(true) * 1000),
                ]);
            } else {

                StockByPeriod::where('item_id', $itemId)
                    ->where('period_by_date', $period)
                    ->update([
                        'last_stock' => $stockByPeriod->last_stock + $value,
                        'updated_at' => round(microtime(true) * 1000),
                    ]);
            }

            Log::info('add stock success');
        } catch (\Throwable $th) {
            Log::error('add stock success', [
                'message' => $th->getMessage()
            ]);
        }
    }

    public function deleteItem(int $itemId): void
    {
        try {
            self::boot();

            StockByPeriod::where('item_id', $itemId)->delete();

            Log::info('delete item success');
        } catch (\Throwable $th) {
            Log::error('delete item failed', [
                'message' => $th->getMessage()
            ]);
        }
    }
}
