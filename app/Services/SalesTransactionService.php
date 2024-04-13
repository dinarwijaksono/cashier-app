<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesTransactionService
{
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
