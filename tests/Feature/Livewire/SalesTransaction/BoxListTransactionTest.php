<?php

namespace Tests\Feature\Livewire\SalesTransaction;

use App\Livewire\Components\AlertDetail;
use App\Livewire\SalesTransaction\BoxListTransaction;
use App\Models\Item;
use App\Services\SalesTransactionService;
use Database\Seeders\ItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Tests\TestCase;

class BoxListTransactionTest extends TestCase
{
    public $salesTransactionService;
    public $item;

    public function setUp(): void
    {
        parent::setUp();

        $this->salesTransactionService = App::make(SalesTransactionService::class);
        $this->seed(ItemSeeder::class);
        $this->item = Item::select('*')->first();
    }

    public function test_renders_successfully()
    {
        $this->salesTransactionService->addItem($this->item->code, 55);

        Livewire::test(BoxListTransaction::class)
            ->assertStatus(200)
            ->assertSee($this->item->name)
            ->assertSee(55)
            ->assertSee(number_format($this->item->price));
    }

    public function test_do_cencel_transaction()
    {
        $this->salesTransactionService->addItem($this->item->code, 55);

        Livewire::test(BoxListTransaction::class)
            ->call('doCencelTransaction');

        Livewire::test(BoxListTransaction::class)
            ->assertDontSee($this->item->name)
            ->assertDontSee(55)
            ->assertDontSee(number_format($this->item->price));
    }

    public function test_process_transaction_success()
    {
        $this->salesTransactionService->addItem($this->item->code, 55);

        Livewire::test(BoxListTransaction::class)
            ->call('doProcess')
            ->assertDispatchedTo(AlertDetail::class, 'do-show-box');

        $this->assertTrue(session()->has('alertDetailMessage'));
        $this->assertEquals(session()->get('alertDetailMessage')['status'], 'success');
        $this->assertEquals(session()->get('alertDetailMessage')['message'], 'Transaksi berhasil di proses.');
    }

    public function test_process_transaction_failed_item_is_empty()
    {
        Livewire::test(BoxListTransaction::class)
            ->call('doProcess')
            ->assertDispatchedTo(AlertDetail::class, 'do-show-box');

        $this->assertTrue(session()->has('alertDetailMessage'));
        $this->assertEquals(session()->get('alertDetailMessage')['status'], 'danger');
        $this->assertEquals(
            session()->get('alertDetailMessage')['message'],
            'Transaksi tidak bisa di proses, karena transaksi kosong.'
        );
    }


    public function test_do_delete_item()
    {
        $this->salesTransactionService->addItem($this->item->code, 55);

        Livewire::test(BoxListTransaction::class)
            ->call('doDeleteItem', $this->item->code);

        Livewire::test(BoxListTransaction::class)
            ->assertDontSee($this->item->name)
            ->assertDontSee(55)
            ->assertDontSee(number_format($this->item->price));
    }
}
