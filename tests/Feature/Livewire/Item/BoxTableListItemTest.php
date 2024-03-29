<?php

namespace Tests\Feature\Livewire\Item;

use App\Livewire\Item\BoxTableListItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BoxTableListItemTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(BoxTableListItem::class)
            ->assertStatus(200);
    }
}
