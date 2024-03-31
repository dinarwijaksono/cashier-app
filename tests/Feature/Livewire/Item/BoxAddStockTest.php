<?php

namespace Tests\Feature\Livewire\Item;

use App\Livewire\Item\BoxAddStock;
use App\Models\Item;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BoxAddStockTest extends TestCase
{
    public function test_renders_successfully()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        Livewire::test(BoxAddStock::class, ['code' => $item->code])
            ->assertStatus(200);
    }


    public function test_addition_success()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        $date = '2024-04-10';

        Livewire::test(BoxAddStock::class, ['code' => $item->code])
            ->set('date', $date)
            ->set('qty', 10)
            ->call('doAddStock');

        $this->assertDatabaseHas('item_stocks', [
            'item_id' => $item->id,
            'stock' => 10,
        ]);

        $date = strtotime($date) * 1000;

        $this->assertDatabaseHas('item_transactions', [
            'item_id' => $item->id,
            'date' => $date,
            'type' => 'add_stock',
            'qty_in' => 10
        ]);

        $this->assertDatabaseHas('stock_by_periods', [
            'item_id' => $item->id,
            'period' => date('F-Y', $date / 1000),
            'last_stock' => 10
        ]);
    }
}
