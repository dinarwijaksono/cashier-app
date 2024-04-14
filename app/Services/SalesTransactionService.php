<?php

namespace App\Services;

use App\Models\DetailedReceipt;
use App\Models\Item;
use App\Models\Receipt;
use App\Repository\ItemStockRepository;
use App\Repository\ItemTransactionRepository;
use App\Repository\StockByPeriodRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesTransactionService
{
    public $itemStockRepository;
    public $stockByPeriodRepository;
    public $itemTransactionRepository;

    public function __construct(
        ItemStockRepository $itemStockRepository,
        StockByPeriodRepository $stockByPeriodRepository,
        ItemTransactionRepository $itemTransactionRepository
    ) {
        $this->itemStockRepository = $itemStockRepository;
        $this->stockByPeriodRepository = $stockByPeriodRepository;
        $this->itemTransactionRepository = $itemTransactionRepository;
    }

    public function boot()
    {
        Log::withContext(['class' => SalesTransactionService::class]);
    }

    public function addItem(string $code, int $qty): void
    {
        try {
            self::boot();

            $transactions = [];

            if (session()->has('transactions')) {
                $transactions = collect(session()->get('transactions'))->toArray();
            }

            $item = DB::table('items')
                ->join('item_stocks', 'item_stocks.item_id', '=', 'items.id')
                ->select(
                    'items.id',
                    'items.code',
                    'items.name',
                    'items.unit',
                    'items.price',
                    'item_stocks.stock',
                    'item_stocks.adjusment',
                    'items.created_at',
                    'items.updated_at'
                )
                ->where('code', $code)
                ->first();

            $t = collect($transactions);

            if ($t->where('code', $code)->isEmpty()) {
                $transactions[] = [
                    'code' => $code,
                    'name' => $item->name,
                    'qty' => $qty,
                    'unit' => $item->unit,
                    'price' => $item->price,
                    'total' => $item->price * $qty
                ];
            } else {

                for ($key = 0; $key < count($transactions); $key++) {

                    if ($transactions[$key]['code'] == $code) {
                        $transactions[$key]['qty'] = $transactions[$key]['qty'] + $qty;
                        $transactions[$key]['total'] = $transactions[$key]['qty'] * $item->price;
                    }
                }
            }

            session()->forget('transactions');
            session()->put('transactions', $transactions);

            Log::info('add item transaction success');
        } catch (\Throwable $th) {
            Log::error('add item transaction failed', [
                'message' => $th->getMessage()
            ]);
        }
    }

    // update
    public function processTransaction(array $transactions): void
    {
        try {
            self::boot();
            DB::beginTransaction();

            $day = date('d', time());
            $month = date('n', time());
            $year = date('Y', time());
            $periodByDate = mktime(0, 0, 0, $month, $day, $year);

            $date = round(microtime(true) * 1000);

            $id = DB::table('receipts')
                ->insertGetId([
                    'period_by_date' => $periodByDate,
                    'date' => $date,
                    'grand_total' => 0,
                    'created_at' => round(microtime(true) * 1000),
                    'updated_at' => round(microtime(true) * 1000),
                ]);

            $grandTotal = 0;
            foreach ($transactions as $key) :

                $grandTotal += $key['total'];

                $item = Item::select('id')->where('code', $key['code'])->first();
                $this->itemStockRepository->addStock($item->id, $key['qty'] * -1);

                $this->stockByPeriodRepository->addStock($item->id, $date, $key['qty'] * -1);

                $this->itemTransactionRepository->create($item->id, $date, 'receipt', $key['qty']);

                DetailedReceipt::insert([
                    'item_id' => $item->id,
                    'receipt_id' => $id,
                    'qty' => $key['qty'],
                    'price' => $key['price'],
                    'total' => $key['total'],
                    'created_at' => round(microtime(true) * 1000),
                    'updated_at' => round(microtime(true) * 1000),
                ]);

            endforeach;

            Receipt::where('id', $id)->update([
                'grand_total' => $grandTotal
            ]);

            session()->forget('transactions');

            Log::info('process transactions success');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('process transactions failed', [
                'message' => $th->getMessage()
            ]);
        }
    }

    // delete
    public function deleteItem(string $code): void
    {
        try {
            self::boot();

            $transactions = [];

            foreach (session()->get('transactions') as $key) {

                if ($key['code'] != $code) {
                    $transactions[] = $key;
                }
            }

            session()->forget('transactions');
            session()->put('transactions', $transactions);

            Log::info('delete item in transactions success');
        } catch (\Throwable $th) {
            Log::error('delete item in transactions failed', [
                'message' => $th->getMessage()
            ]);
        }
    }
}
