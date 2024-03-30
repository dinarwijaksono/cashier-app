<?php

namespace Tests\Feature\Service;

use App\Domain\ItemDomain;
use App\Models\Item;
use App\Services\ItemService;
use Database\Seeders\ItemSeeder;
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


    public function test_getByCode_success()
    {
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        $response = $this->itemService->getByCode($item->code);

        $this->assertEquals($item->name, $response->name);
        $this->assertEquals($item->price, $response->price);
        $this->assertEquals($item->unit, $response->unit);

        $this->assertObjectHasProperty('stock', $response);
    }

    public function test_getByCode_code_is_empty()
    {
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);

        $response = $this->itemService->getByCode('I000');

        $this->assertIsObject($response);
    }

    public function test_getByName()
    {
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);

        $name = 'example';
        $unit = 'kg';
        $price = 1000;

        $this->itemService->create($name, $unit, $price);

        $name = 'example two';

        $this->itemService->create($name, $unit, $price);

        $name = 'this example two';

        $this->itemService->create($name, $unit, $price);

        $response = $this->itemService->getByname('example');

        $this->assertEquals($response->count(), 3);

        $response = $this->itemService->getAll();

        $this->assertEquals($response->count(), 6);
    }


    public function test_getAll()
    {
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);

        $response = $this->itemService->getALl();

        $this->assertEquals($response->count(), 5);

        $first = $response->first();

        $this->assertObjectHasProperty('name', $first);
        $this->assertObjectHasProperty('adjusment', $first);
    }


    public function test_getItemChangeHistory()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        $itemDomain = new ItemDomain();
        $itemDomain->code = $item->code;
        $itemDomain->name = 'example';
        $itemDomain->price = 1000;
        $itemDomain->unit = 'pcs';

        $this->itemService->updateByCode($itemDomain);

        $response = $this->itemService->getItemChangeHistoryByItemId($item->id);
        $first = collect($response->first())->toArray();

        $this->assertArrayHasKey('before_name', $first);
        $this->assertArrayHasKey('before_unit', $first);
        $this->assertArrayHasKey('before_price', $first);
        $this->assertArrayHasKey('created_at', $first);
    }


    public function test_updateByCode()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        $itemDomain = new ItemDomain();
        $itemDomain->code = $item->code;
        $itemDomain->name = 'example';
        $itemDomain->price = 1000;
        $itemDomain->unit = 'pcs';

        $this->itemService->updateByCode($itemDomain);

        $this->assertDatabaseHas('items', [
            'name' => $itemDomain->name,
            'unit' => $itemDomain->unit,
            'price' => $itemDomain->price,
        ]);

        $this->assertDatabaseHas('item_change_histories', [
            'before_name' => $itemDomain->name,
            'before_unit' => $itemDomain->unit,
            'before_price' => $itemDomain->price,
        ]);
    }
}
