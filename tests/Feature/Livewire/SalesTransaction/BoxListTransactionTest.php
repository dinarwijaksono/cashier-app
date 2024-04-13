<?php

namespace Tests\Feature\Livewire\SalesTransaction;

use App\Livewire\SalesTransaction\BoxListTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BoxListTransactionTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(BoxListTransaction::class)
            ->assertStatus(200);
    }
}
