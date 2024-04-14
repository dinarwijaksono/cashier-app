<?php

namespace Tests\Feature\Livewire\Receipt;

use App\Livewire\Receipt\BoxTransaction;
use App\Models\Item;
use Database\Seeders\ItemSeeder;
use Database\Seeders\ReceiptSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BoxTransactionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);
    }

    public function test_renders_successfully()
    {
        $this->seed(ReceiptSeeder::class);

        $item = Item::select('*')->first();

        Livewire::test(BoxTransaction::class)
            ->assertStatus(200)
            ->assertSee($item->name);
    }
}
