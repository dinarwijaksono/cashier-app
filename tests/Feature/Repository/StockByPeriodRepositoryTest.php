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
    public $item;
    public $date;

    public function setUp(): void
    {
        parent::setUp();

        $this->stockByPeriodRepository = App::make(StockByPeriodRepository::class);

        $this->seed(ItemSeeder::class);
        $this->item = Item::select('*')->first();
        $this->date = mktime(0, 0, 0, 5, 11, 2024) * 1000;
    }

    public function test_addStock_success(): void
    {
        $this->stockByPeriodRepository->addStock($this->item->id, $this->date, 10);

        $period = date('F-Y', $this->date / 1000);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $this->item->id,
            'period' => $period,
            'last_stock' => 10
        ]);

        $date = mktime(0, 0, 0, 5, 14, 2024) * 1000;

        $this->stockByPeriodRepository->addStock($this->item->id, $date, 15);

        $period = date('F-Y', $date / 1000);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $this->item->id,
            'period' => $period,
            'last_stock' => 25
        ]);
    }


    public function test_add_stock_negative()
    {
        $this->stockByPeriodRepository->addStock($this->item->id, $this->date, 10);

        $period = date('F-Y', $this->date / 1000);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $this->item->id,
            'period' => $period,
            'last_stock' => 10
        ]);

        $date = mktime(0, 0, 0, 5, 14, 2024) * 1000;

        $this->stockByPeriodRepository->addStock($this->item->id, $date, -5);

        $period = date('F-Y', $date / 1000);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $this->item->id,
            'period' => $period,
            'last_stock' => 5
        ]);
    }


    public function test_delete_item_success()
    {
        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $this->item->id,
            'first_stock' => 0,
            'adjusment' => 0,
            'last_stock' => 0
        ]);

        $this->stockByPeriodRepository->deleteItem($this->item->id);

        $this->assertDatabaseMissing('stock_by_periods', [
            'item_id' => $this->item->id,
        ]);
    }
}
