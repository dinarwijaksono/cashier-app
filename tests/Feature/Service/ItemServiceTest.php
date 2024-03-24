<?php

namespace Tests\Feature\Service;

use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemServiceTest extends TestCase
{
    public $itemService;

    public function setUp(): void
    {
        parent::setUp();

        $this->itemService = $this->app->make(ItemService::class);
    }

    public function test_create_success(): void
    {
        $name = 'test-' . random_int(1, 999);
        $unit = 'kg';
        $price = 1000;

        $this->itemService->create($name, $unit, $price);

        $this->assertDatabaseHas('items', [
            'name' => $name,
            'unit' => $unit,
            'price' => $price
        ]);

        $this->assertDatabaseHas('item_change_histories', [
            'before_name' => $name,
            'before_unit' => $unit,
        ]);

        $item = Item::select('id')->where('name', $name)->first();

        $this->assertDatabaseHas('item_stocks', [
            'item_id' => $item->id,
            'stock' => 0,
            'adjusment' => 0
        ]);
    }
}
