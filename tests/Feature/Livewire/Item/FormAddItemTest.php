<?php

namespace Tests\Feature\Livewire\Item;

use App\Livewire\Item\FormAddItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class FormAddItemTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(FormAddItem::class)
            ->assertStatus(200);
    }
}
