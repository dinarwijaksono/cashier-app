<?php

namespace App\Services;

use App\Models\DetailedReceipt;
use App\Models\Receipt;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class ReceiptService
{
    public function boot(): void
    {
        Log::withContext(['class' => ReceiptService::class]);
    }

    // read
    public function getTransactions(): Collection
    {
        try {

            $receipt = Receipt::select(
                'id',
                'period_by_date',
                'date',
                'grand_total',
                'created_at',
                'updated_at'
            )->get();

            $detailedReceipt = DB::table('detailed_receipts')
                ->join('items', 'detailed_receipts.item_id', '=', 'items.id')
                ->select(
                    'detailed_receipts.item_id',
                    'detailed_receipts.receipt_id',
                    'items.code',
                    'items.name',
                    'items.unit',
                    'detailed_receipts.qty',
                    'detailed_receipts.price',
                    'detailed_receipts.total'
                )
                ->get();

            $transactions = collect([]);

            foreach ($receipt  as $key) :
                $item = new stdClass();
                $item->period_by_date = $key->period_by_date;
                $item->date = $key->date;
                $item->items = $detailedReceipt->where('receipt_id', $key->id);
                $item->grand_total = $key->grand_total;
                $item->created_at = $key->created_at;
                $item->updated_at = $key->updated_at;

                $transactions->push($item);
            endforeach;

            Log::info("get transaction success");

            return collect($transactions);
        } catch (\Throwable $th) {
            Log::error("get transaction failed", [
                'message' => $th->getMessage()
            ]);

            return collect([]);
        }
    }
}
