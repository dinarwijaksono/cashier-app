<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemChangeHistory;
use App\Models\ItemStock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemService
{
    public function boot()
    {
        Log::withContext(['class' => ItemService::class]);
    }

    // create
    public function create(string $name, string $unit, float $price): void
    {
        self::boot();

        try {

            $itemId = DB::table('items')->insertGetId([
                'code' => '0',
                'name' => $name,
                'unit' => $unit,
                'price' => $price,
                'created_at' => round(microtime(true) * 1000),
                'updated_at' => round(microtime(true) * 1000),
            ]);

            Item::where('id', $itemId)->update([
                'code' =>  'I' . str_pad($itemId, 7, '0', STR_PAD_LEFT),
            ]);

            $itemChangeHistory = new ItemChangeHistory();
            $itemChangeHistory->item_id = $itemId;
            $itemChangeHistory->before_name = $name;
            $itemChangeHistory->before_unit = $unit;
            $itemChangeHistory->before_price = $price;
            $itemChangeHistory->created_at = round(microtime(true) * 1000);
            $itemChangeHistory->updated_at = round(microtime(true) * 1000);
            $itemChangeHistory->save();

            $itemStock = new ItemStock();
            $itemStock->item_id = $itemId;
            $itemStock->stock = 0;
            $itemStock->adjusment = 0;
            $itemStock->created_at = round(microtime(true) * 1000);
            $itemStock->updated_at = round(microtime(true) * 1000);
            $itemStock->save();

            Log::info('create item success');
        } catch (\Throwable $th) {

            Log::error('create item failed', [
                'exeption' => $th->getMessage()
            ]);
        }
    }


    // read
    public function getAll(): Collection
    {
        try {

            $getItem = DB::table('items')
                ->join('item_stocks', 'item_stocks.item_id', '=', 'items.id')
                ->select(
                    'items.id',
                    'items.code',
                    'items.name',
                    'items.unit',
                    'items.unit',
                    'item_stocks.stock',
                    'item_stocks.adjusment',
                    'items.created_at',
                    'items.updated_at'
                )->get();

            Log::info('get all item success');

            return collect($getItem);
        } catch (\Throwable $th) {
            Log::error('get all item failed', [
                'exeption' => $th->getMessage()
            ]);

            return collect();
        }
    }
}
