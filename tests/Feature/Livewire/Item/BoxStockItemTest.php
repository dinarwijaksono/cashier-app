<?php

namespace Tests\Feature\Livewire\Item;

use App\Livewire\Item\BoxStockItem;
use App\Models\Item;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BoxStockItemTest extends TestCase
{
    public function test_renders_successfully()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        Livewire::test(BoxStockItem::class, ['code' => $item->code])
            ->assertStatus(200)
            ->assertSee($item->name)
            ->assertSee($item->unit);
    }
}
