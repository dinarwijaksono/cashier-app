<?php

namespace Tests\Feature\Livewire\Item;

use App\Livewire\Item\TableListItem;
use App\Models\Item;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TableListItemTest extends TestCase
{
    public function test_renders_successfully()
    {
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        Livewire::test(TableListItem::class)
            ->assertStatus(200)
            ->assertSee($item->name)
            ->assertSee(number_format($item->price));
    }
}
