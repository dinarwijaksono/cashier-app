<?php

namespace Tests\Feature\Livewire\Item;

use App\Livewire\Item\BoxStockItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BoxStockItemTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(BoxStockItem::class)
            ->assertStatus(200);
    }
}
