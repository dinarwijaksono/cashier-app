<?php

namespace Tests\Feature\Service;

use App\Models\Item;
use App\Services\ItemTransactionService;
use Database\Seeders\AddStockSeeder;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ItemTransactionSeriviceTest extends TestCase
{
    public $itemTransactionService;

    public function setUp(): void
    {
        parent::setUp();

        $this->itemTransactionService = App::make(ItemTransactionService::class);

        $this->seed(ItemSeeder::class);
    }

    public function test_get_transaction_success(): void
    {
        $this->seed(AddStockSeeder::class);

        $response = $this->itemTransactionService->getTransactions();

        $item = Item::select('*')->first();

        $this->assertTrue($response->where('item_id', $item->id)->isNotEmpty());
        $this->assertFalse($response->where('item_id', 'd')->isNotEmpty());
    }
}
