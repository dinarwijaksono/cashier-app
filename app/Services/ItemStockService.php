<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemStock;
use App\Models\ItemTransaction;
use App\Models\StockByPeriod;
use App\Repository\ItemTransactionRepository;
use App\Repository\StockByPeriodRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class ItemStockService
{
    public $stockByPeriodRepository;
    public $itemTransactionRepository;

    public function __construct(
        StockByPeriodRepository $stockByPeriodRepository,
        ItemTransactionRepository $itemTransactionRepository
    ) {
        $this->stockByPeriodRepository = $stockByPeriodRepository;
        $this->itemTransactionRepository = $itemTransactionRepository;
    }

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

            $this->stockByPeriodRepository->addStock($itemId, $date, $value);

            $this->itemTransactionRepository->create($itemId, $date, 'add_stock', $value);

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
                    'items.unit',
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
                ->orderBy('stock_by_periods.period_by_date')
                ->first();

            Log::info('get item stock by code success');

            return $itemStock;
        } catch (\Throwable $th) {
            Log::error('get item stock by code failed', [
                'message' => $th->getMessage()
            ]);
        }
    }


    public function getItemTransactions(string $code): Collection
    {
        try {
            $item = Item::select("id")->where('code', $code)->first();

            $itemTransactions = ItemTransaction::select(
                'item_id',
                'period_by_date',
                'date',
                'type',
                'qty_in',
                'qty_out',
                'created_at',
                'updated_at',
            )->where('item_id', $item->id)
                ->get();

            Log::info('get item transactions success');

            return $itemTransactions;
        } catch (\Throwable $th) {
            Log::error('get item transactions failed', [
                'message' => $th->getMessage()
            ]);

            return collect([]);
        }
    }
}
