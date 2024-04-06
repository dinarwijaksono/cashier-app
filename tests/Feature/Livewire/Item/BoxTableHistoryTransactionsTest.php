<?php

namespace Tests\Feature\Livewire\Item;

use App\Livewire\Item\BoxTableHistoryTransactions;
use App\Models\Item;
use App\Models\ItemTransaction;
use App\Services\ItemStockService;
use Database\Seeders\AddStockSeeder;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Tests\TestCase;

class BoxTableHistoryTransactionsTest extends TestCase
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

    public function test_renders_successfully()
    {
        Livewire::test(BoxTableHistoryTransactions::class, ['code' => $this->item->code])
            ->assertStatus(200);
    }


    public function test_do_delete_transaction()
    {
        $this->seed(AddStockSeeder::class);

        $transaction = ItemTransaction::select('*')->first();

        Livewire::test(BoxTableHistoryTransactions::class, ['code' => $this->item->code])
            ->call('doDeleteTransaction', $transaction->id);

        $this->assertDatabaseMissing('item_transactions', [
            'id' => $transaction->id
        ]);

        $this->assertTrue(session()->has('alertMessage'));
    }
}
