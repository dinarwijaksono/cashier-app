<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemStock;
use App\Models\ItemTransaction;
use App\Models\StockByPeriod;
use App\Repository\ItemStockRepository;
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
    public $itemStockRepository;

    public function __construct(
        StockByPeriodRepository $stockByPeriodRepository,
        ItemTransactionRepository $itemTransactionRepository,
        ItemStockRepository $itemStockRepository,
    ) {
        $this->stockByPeriodRepository = $stockByPeriodRepository;
        $this->itemTransactionRepository = $itemTransactionRepository;
        $this->itemStockRepository = $itemStockRepository;
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

            $this->itemStockRepository->addStock($itemId, $value);

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
            self::boot();

            $item = Item::select("id")->where('code', $code)->first();

            $itemTransactions = ItemTransaction::select(
                'id',
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


    // delete 
    public function deleteTransaction(int $transactionId): void
    {
        try {
            self::boot();
            DB::beginTransaction();

            $itemTransaction = ItemTransaction::select(
                'id',
                'item_id',
                'date',
                'qty_in',
            )->where('id', $transactionId)
                ->first();

            $value = $itemTransaction->qty_in * -1;

            $this->itemStockRepository->addStock($itemTransaction->item_id, $value);

            $this->stockByPeriodRepository->addStock(
                $itemTransaction->item_id,
                $itemTransaction->date,
                $value
            );

            ItemTransaction::where('id', $itemTransaction->id)->delete();

            Log::info('delete transaction success');
            DB::commit();
        } catch (\Throwable $th) {

            Log::error('delete transaction failed', [
                'message' => $th->getMessage()
            ]);

            DB::rollBack();
        }
    }


    public function deleteItem(int $itemId): void
    {
        try {
            self::boot();

            DB::beginTransaction();

            $item = ItemTransaction::select('item_id')->where('item_id', $itemId)->get();
            if ($item->isNotEmpty()) {
                Log::error('delete item failed', [
                    'message' => 'the item still exists in the transaction table'
                ]);
            } else {

                $this->stockByPeriodRepository->deleteItem($itemId);
                $this->itemStockRepository->deleteItem($itemId);

                Item::where('id', $itemId)->delete();

                Log::info('delete item success');
                DB::commit();
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('delete item failed', [
                'message' => $th->getMessage()
            ]);
        }
    }
}
