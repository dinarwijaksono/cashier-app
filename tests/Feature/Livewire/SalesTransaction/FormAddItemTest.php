<?php

namespace Tests\Feature\Livewire\SalesTransaction;

use App\Livewire\SalesTransaction\FormAddItem;
use App\Models\Item;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class FormAddItemTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(FormAddItem::class)
            ->assertStatus(200);
    }

    public function test_do_add_item_success()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select("*")->first();

        Livewire::test(FormAddItem::class)
            ->set('name', $item->name)
            ->set('qty', 10)
            ->call('doAddItem');

        $this->assertTrue(session()->has('transactions'));

        $transactions = session()->get('transactions');

        $this->assertEquals($transactions[0]['qty'], 10);
    }
}
