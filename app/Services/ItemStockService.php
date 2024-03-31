<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemStock;
use App\Models\ItemTransaction;
use App\Models\StockByPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class ItemStockService
{
    public function boot()
    {
        Log::withContext(['class' => ItemStockService::class]);
    }

    // create
    public function addition(int $itemId, int $date, int $value): void
    {
        try {

            self::boot();

            DB::beginTransaction();

            $item = ItemStock::select('id', 'item_id', 'stock')
                ->where('item_id', $itemId)
                ->first();

            ItemStock::where('item_id', $itemId)
                ->update([
                    'stock' => $item->stock + $value,
                    'updated_at' => round(microtime(true) * 1000)
                ]);

            $month = date('n', $date / 1000);
            $year = date('Y', $date / 1000);
            $period = mktime(0, 0, 0, $month, 1, $year);
            $period = $period * 1000;

            $stockByPeriod = StockByPeriod::select('item_id')
                ->where('item_id', $itemId)
                ->where('period_by_date', $period)
                ->orderByDesc('period_by_date')
                ->get();

            $stockByPeriod = collect($stockByPeriod);

            if ($stockByPeriod->isEmpty()) {

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

                $stockByPeriod = StockByPeriod::select('item_id', 'period_by_date', 'is_closed', 'last_stock')
                    ->where('item_id', $itemId)
                    ->where('period_by_date', $period)
                    ->orderByDesc('period_by_date')
                    ->first();

                StockByPeriod::where('item_id', $itemId)
                    ->where('period_by_date', $period)
                    ->update([
                        'last_stock' => $stockByPeriod->last_stock + $value,
                        'updated_at' => round(microtime(true) * 1000),
                    ]);
            }

            ItemTransaction::insert([
                'item_id' => $itemId,
                'period_by_date' => $period,
                'date' => $date,
                'type' => 'add_stock',
                'qty_in' => $value,
                'qty_out' => 0,
                'created_at' => round(microtime(true) * 1000),
                'updated_at' => round(microtime(true) * 1000),
            ]);

            Log::info('addtion success');

            DB::commit();
        } catch (\Throwable $th) {

            Log::error('addtion failed', [
                'message' => $th->getMessage()
            ]);

            DB::rollBack();
        }
    }


    // read
    public function getItemStockByCode(string $code): object
    {
        try {
            self::boot();

            $item = Item::select('id')->where('code', $code)->first();

            $itemStock = DB::table('stock_by_periods')
                ->join('items', 'items.id', '=', 'stock_by_periods.item_id')
                ->select(
                    'items.name',
                    'items.code',
                    'stock_by_periods.period',
                    'stock_by_periods.period_by_date',
                    'stock_by_periods.is_closed',
                    'stock_by_periods.first_stock',
                    'stock_by_periods.adjusment',
                    'stock_by_periods.last_stock',
                    'stock_by_periods.created_at',
                    'stock_by_periods.updated_at'
                )->where('stock_by_periods.item_id', $item->id)
                ->where('stock_by_periods.is_closed', false)
                ->first();

            Log::info('get item stock by code success');

            return $itemStock;
        } catch (\Throwable $th) {
            Log::error('get item stock by code failed', [
                'message' => $th->getMessage()
            ]);
        }
    }
}
