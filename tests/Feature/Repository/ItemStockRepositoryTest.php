<?php

namespace Tests\Feature\Repository;

use App\Models\Item;
use App\Repository\ItemStockRepository;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ItemStockRepositoryTest extends TestCase
{
    public $itemStockRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->itemStockRepository = App::make(ItemStockRepository::class);
    }

    public function test_add_stock(): void
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        $this->itemStockRepository->addStock($item->id, 14);
        $this->itemStockRepository->addStock($item->id, 20);

        $this->assertDatabaseHas('item_stocks', [
            'item_id' => $item->id,
            'stock' => 34
        ]);
    }
}
