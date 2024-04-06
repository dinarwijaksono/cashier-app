<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Services\ItemStockService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddStockSeeder extends Seeder
{
    public $itemStockService;

    public function __construct(ItemStockService $itemStockService)
    {
        $this->itemStockService = $itemStockService;
    }


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item = Item::select('*')->first();

        $date = time() * 1000;

        $this->itemStockService->addition($item->id, $date, 17);
    }
}
