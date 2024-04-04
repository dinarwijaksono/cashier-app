<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Repository\ItemTransactionRepository;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ItemTransactionRepositoryTest extends TestCase
{
    public $itemTransactionRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->itemTransactionRepository = App::make(ItemTransactionRepository::class);
    }

    public function test_create_success_add_stock(): void
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();
        $date = mktime(0, 0, 14, 7, 15, 2024) * 1000;

        $this->itemTransactionRepository->create($item->id, $date, 'add_stock', 11);

        $this->assertDatabaseHas('item_transactions', [
            'item_id' => $item->id,
            'qty_in' => 11,
            'qty_out' => 0,
            'date' => $date
        ]);
    }

    public function test_create_success_receipt()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();
        $date = mktime(0, 0, 14, 7, 15, 2024) * 1000;

        $this->itemTransactionRepository->create($item->id, $date, 'receipt', 22);

        $this->assertDatabaseHas('item_transactions', [
            'item_id' => $item->id,
            'qty_in' => 0,
            'qty_out' => 22,
            'date' => $date
        ]);
    }

    public function test_create_success_internal_use()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();
        $date = mktime(0, 0, 14, 7, 15, 2024) * 1000;

        $this->itemTransactionRepository->create($item->id, $date, 'internal_use', 7);

        $this->assertDatabaseHas('item_transactions', [
            'item_id' => $item->id,
            'qty_in' => 0,
            'qty_out' => 7,
            'date' => $date
        ]);
    }
}
