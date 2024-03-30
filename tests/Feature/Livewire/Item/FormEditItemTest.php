<?php

namespace Tests\Feature\Livewire\Item;

use App\Livewire\Item\FormEditItem;
use App\Models\Item;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class FormEditItemTest extends TestCase
{
    public function test_renders_successfully()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        Livewire::test(FormEditItem::class, ['code' => $item->code])
            ->assertStatus(200);
    }


    public function test_do_edit_item()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        $name = 'example';
        $unit = 'liter';
        $price = 14000;

        Livewire::test(FormEditItem::class, ['code' => $item->code])
            ->set('itemName', $name)
            ->set('unit', $unit)
            ->set('price', $price)
            ->call('doEditItem');

        $this->assertDatabaseHas('item_change_histories', [
            'before_name' => $name,
            'before_unit' => $unit,
            'before_price' => $price
        ]);
    }
}
