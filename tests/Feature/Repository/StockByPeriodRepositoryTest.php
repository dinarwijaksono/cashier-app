<?php

namespace Tests\Feature\Repository;

use App\Models\Item;
use App\Repository\StockByPeriodRepository;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class StockByPeriodRepositoryTest extends TestCase
{
    public $stockByPeriodRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->stockByPeriodRepository = App::make(StockByPeriodRepository::class);
    }

    public function test_addStock_success(): void
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        $date = mktime(0, 0, 0, 5, 11, 2024) * 1000;

        $this->stockByPeriodRepository->addStock($item->id, $date, 10);

        $period = date('F-Y', $date / 1000);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $item->id,
            'period' => $period,
            'last_stock' => 10
        ]);

        $date = mktime(0, 0, 0, 5, 14, 2024) * 1000;

        $this->stockByPeriodRepository->addStock($item->id, $date, 15);

        $period = date('F-Y', $date / 1000);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $item->id,
            'period' => $period,
            'last_stock' => 25
        ]);
    }


    public function test_add_stock_negative()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        $date = mktime(0, 0, 0, 5, 11, 2024) * 1000;

        $this->stockByPeriodRepository->addStock($item->id, $date, 10);

        $period = date('F-Y', $date / 1000);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $item->id,
            'period' => $period,
            'last_stock' => 10
        ]);

        $date = mktime(0, 0, 0, 5, 14, 2024) * 1000;

        $this->stockByPeriodRepository->addStock($item->id, $date, -5);

        $period = date('F-Y', $date / 1000);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $item->id,
            'period' => $period,
            'last_stock' => 5
        ]);
    }
}
