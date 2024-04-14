<?php

namespace Tests\Feature\Service;

use App\Models\Item;
use App\Models\ItemStock;
use App\Services\SalesTransactionService;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class SalesTransactionServiceTest extends TestCase
{
    public $salesTransactionService;
    public $item;

    public function setUp(): void
    {
        parent::setUp();

        $this->salesTransactionService = App::make(SalesTransactionService::class);

        $this->seed(ItemSeeder::class);
        $this->item = Item::select('*')->first();
    }


    public function test_add_item_success()
    {
        $qty = 5;

        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);

        $this->salesTransactionService->addItem($this->item->code, $qty);
        $this->salesTransactionService->addItem($this->item->code, $qty);

        $item = Item::select('*')->where('code', '!=', $this->item->code)->first();

        $this->salesTransactionService->addItem($item->code, 4);

        $transaction = collect(session()->get('transactions'));

        $this->assertTrue(session()->has('transactions'));

        $this->assertEquals($transaction->where('code', $this->item->code)->first()['name'], $this->item->name);
        $this->assertEquals($transaction->where('code', $this->item->code)->first()['qty'], 10);
        $this->assertEquals($transaction->where('code', $item->code)->first()['qty'], 4);
    }

    public function test_process_transaction()
    {
        $this->seed(ItemSeeder::class);
        $itemStock = ItemStock::select('stock')->where('item_id', $this->item->id)->first();

        $qty = 10;

        $item = Item::select('*')->where('code', '!=', $this->item->code)->first();

        $this->salesTransactionService->addItem($this->item->code, $qty);
        $this->salesTransactionService->addItem($item->code, 10);

        $transactions = session()->get('transactions');
        $this->salesTransactionService->processTransaction($transactions);

        $this->assertDatabaseHas('detailed_receipts', [
            'item_id' => $this->item->id,
            'qty' => $qty,
            'price' => $this->item->price,
            'total' => $this->item->price * $qty,
        ]);

        $this->assertDatabaseHas('detailed_receipts', [
            'item_id' => $item->id,
            'qty' => 10,
            'price' => $item->price,
            'total' => $item->price * 10,
        ]);

        $this->assertDatabaseHas('receipts', [
            'grand_total' => $this->item->price * 10 + $item->price * 10
        ]);

        $itemStockNew = ItemStock::select('stock')->where('item_id', $this->item->id)->first();

        $this->assertNotEquals($itemStock->stock, $itemStockNew->stock);
        $this->assertFalse(session()->has('transactions'));
    }


    public function test_delete_item_success()
    {
        $qty = 5;

        $this->seed(ItemSeeder::class);


        $this->salesTransactionService->addItem($this->item->code, $qty);

        $item = Item::select('*')->where('code', '!=', $this->item->code)->first();
        $this->salesTransactionService->addItem($item->code, 4);

        $transaction = collect(session()->get('transactions'));
        $this->assertTrue($transaction->where('code', $item->code)->isNotEmpty());

        $this->salesTransactionService->deleteItem($item->code);

        $transaction = collect(session()->get('transactions'));
        $this->assertTrue($transaction->where('code', $item->code)->isEmpty());
    }
}
