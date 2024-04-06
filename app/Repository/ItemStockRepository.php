<?php

namespace App\Repository;

use App\Models\ItemStock;
use Illuminate\Support\Facades\Log;

class ItemStockRepository
{
    public function boot()
    {
        Log::withContext(['class' => ItemStockRepository::class]);
    }

    // create
    public function addStock(int $itemId, int $value): void
    {
        try {
            self::boot();

            $item = ItemStock::select('id', 'item_id', 'stock')
                ->where('item_id', $itemId)
                ->first();

            ItemStock::where('item_id', $itemId)
                ->update([
                    'stock' => $item->stock + $value,
                    'updated_at' => round(microtime(true) * 1000)
                ]);

            Log::info('add stock success');
        } catch (\Throwable $th) {
            Log::error('add stock failed', [
                'message' => $th->getMessage()
            ]);
        }
    }


    // delete
    public function deleteItem(int $itemId): void
    {
        try {
            self::boot();

            ItemStock::where('item_id', $itemId)->delete();

            Log::info('delete item success');
        } catch (\Throwable $th) {
            Log::error('delete item failed', [
                'message' => $th->getMessage()
            ]);
        }
    }
}
