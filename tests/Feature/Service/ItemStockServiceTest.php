<?php

namespace Tests\Feature\Service;

use App\Models\Item;
use App\Models\ItemTransaction;
use App\Services\ItemStockService;
use Database\Seeders\AddStockSeeder;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ItemStockServiceTest extends TestCase
{
    public $itemStockService;
    public $item;

    public function setUp(): void
    {
        parent::setUp();

        $this->itemStockService = App::make(ItemStockService::class);

        $this->seed(ItemSeeder::class);

        $this->item = Item::select('*')->first();
    }


    public function test_addition(): void
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        $date = mktime(1, 2, 3, 1, 12, 2000);
        $date = $date * 1000;

        $this->itemStockService->addition($item->id, $date, 10);

        $this->assertDatabaseHas('item_stocks', [
            'item_id' => $item->id,
            'stock' => 10,
            'adjusment' => 0
        ]);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $item->id,
            'last_stock' => 10
        ]);

        $date = mktime(1, 2, 3, 1, 13, 2000);
        $date = $date * 1000;
        $this->itemStockService->addition($item->id, $date, 30);

        $this->assertDatabaseHas('item_stocks', [
            'item_id' => $item->id,
            'stock' => 40,
            'adjusment' => 0
        ]);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $item->id,
            'last_stock' => 40
        ]);

        $this->assertDatabaseHas('item_transactions', [
            'item_id' => $item->id,
            'type' => 'add_stock',
            'qty_in' => 10,
            'qty_out' => 0,
        ]);

        $this->assertDatabaseHas('item_transactions', [
            'item_id' => $item->id,
            'type' => 'add_stock',
            'qty_in' => 30,
            'qty_out' => 0,
        ]);
    }


    public function test_getItemStockByCode_success()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();
        $date = mktime(1, 2, 3, 1, 12, 2000);
        $date = $date * 1000;
        $this->itemStockService->addition($item->id, $date, 10);

        $response = $this->itemStockService->getItemStockByCode($item->code);

        $this->assertEquals($response->name, $item->name);
        $this->assertEquals($response->first_stock, 0);
        $this->assertEquals($response->adjusment, 0);
        $this->assertEquals($response->last_stock, 10);
    }


    public function test_getItemStockByCode_success_with_data_empty()
    {
        $this->seed(ItemSeeder::class);
        $item = Item::select('*')->first();

        $response = $this->itemStockService->getItemStockByCode($item->code);

        $this->assertEquals($response->name, $item->name);
        $this->assertEquals($response->first_stock, 0);
        $this->assertEquals($response->adjusment, 0);
        $this->assertEquals($response->last_stock, 0);
    }


    public function test_get_item_stock_by_code()
    {
        $this->seed(ItemSeeder::class);
        $item = Item::select('*')->first();

        $date = mktime(1, 2, 3, 1, 12, 2000);
        $date = $date * 1000;

        $this->itemStockService->addition($item->id, $date, 10);

        $this->assertDatabaseHas('item_transactions', [
            'item_id' => $item->id,
            'date' => $date,
            'type' => 'add_stock',
            'qty_in' => 10,
            'qty_out' => 0,
        ]);
    }


    public function test_delete_transaction()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();
        $date = mktime(1, 2, 3, 1, 12, 2004);
        $date = $date * 1000;
        $this->itemStockService->addition($item->id, $date, 13);

        $transaction = ItemTransaction::select('*')->first();

        $this->assertDatabaseHas('item_stocks', [
            'item_id' => $item->id,
            'stock' => 13,
            'adjusment' => 0
        ]);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $item->id,
            'last_stock' => 13
        ]);

        $this->assertDatabaseHas('item_transactions', [
            'item_id' => $item->id,
            'type' => 'add_stock',
            'qty_in' => 13,
            'qty_out' => 0,
        ]);

        $this->itemStockService->deleteTransaction($transaction->id);

        $this->assertDatabaseMissing('item_stocks', [
            'item_id' => $item->id,
            'stock' => 13,
            'adjusment' => 0
        ]);

        $this->assertDatabaseMissing('stock_by_periods', [
            'item_id' => $item->id,
            'last_stock' => 13
        ]);

        $this->assertDatabaseMissing('item_transactions', [
            'item_id' => $item->id,
            'type' => 'add_stock',
            'qty_in' => 13,
            'qty_out' => 0,
        ]);
    }


    public function test_delete_success()
    {
        $this->assertDatabaseHas('items', ['id' => $this->item->id]);
        $this->assertDatabaseHas('item_stocks', ['item_id' => $this->item->id]);
        $this->assertDatabaseHas('stock_by_periods', ['item_id' => $this->item->id]);

        $this->itemStockService->deleteItem($this->item->id);

        $this->assertDatabaseMissing('items', ['id' => $this->item->id]);
        $this->assertDatabaseMissing('item_stocks', ['item_id' => $this->item->id]);
        $this->assertDatabaseMissing('stock_by_periods', ['item_id' => $this->item->id]);
    }

    public function test_delete_failed_item_exist_in_transaction()
    {
        $this->seed(AddStockSeeder::class);

        $this->assertDatabaseHas('items', ['id' => $this->item->id]);
        $this->assertDatabaseHas('item_stocks', ['item_id' => $this->item->id]);
        $this->assertDatabaseHas('stock_by_periods', ['item_id' => $this->item->id]);

        $this->itemStockService->deleteItem($this->item->id);

        $this->assertDatabaseHas('items', ['id' => $this->item->id]);
        $this->assertDatabaseHas('item_stocks', ['item_id' => $this->item->id]);
        $this->assertDatabaseHas('stock_by_periods', ['item_id' => $this->item->id]);
    }
}
