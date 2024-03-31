<?php

namespace Tests\Feature\Livewire\Item;

use App\Livewire\Item\BoxTableChangeItemHistory;
use App\Models\Item;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BoxTableChangeItemHistoryTest extends TestCase
{
    public function test_renders_successfully()
    {
        $this->seed(ItemSeeder::class);

        $item = Item::select('*')->first();

        Livewire::test(BoxTableChangeItemHistory::class, ['code' => $item->code])
            ->assertStatus(200);
    }
}
