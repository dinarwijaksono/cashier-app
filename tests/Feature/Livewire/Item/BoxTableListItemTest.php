<?php

namespace Tests\Feature\Livewire\Item;

use App\Livewire\Components\Alert;
use App\Livewire\Item\BoxTableListItem;
use App\Models\Item;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BoxTableListItemTest extends TestCase
{
    public $item;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(ItemSeeder::class);
        $this->item = Item::select('*')->first();
    }

    public function test_renders_successfully()
    {
        Livewire::test(BoxTableListItem::class)
            ->assertStatus(200);
    }

    public function test_do_delete_item()
    {
        Livewire::test(BoxTableListItem::class)
            ->call('doDeleteItem', $this->item->id)
            ->assertDispatchedTo(Alert::class, 'do-show-box');

        $this->assertTrue(session()->has('alertMessage'));

        $this->assertDatabaseMissing('items', ['id' => $this->item->id]);
        $this->assertDatabaseMissing('item_stocks', ['item_id' => $this->item->id]);
        $this->assertDatabaseMissing('stock_by_periods', ['item_id' => $this->item->id]);
    }
}
