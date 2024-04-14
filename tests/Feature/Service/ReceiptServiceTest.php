<?php

namespace Tests\Feature\Service;

use App\Services\ReceiptService;
use Database\Seeders\ItemSeeder;
use Database\Seeders\ReceiptSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ReceiptServiceTest extends TestCase
{
    public $receiptService;

    public function setUp(): void
    {
        parent::setUp();

        $this->receiptService = App::make(ReceiptService::class);

        $this->seed(ItemSeeder::class);
        $this->seed(ItemSeeder::class);
    }

    public function test_get_transaction(): void
    {
        $this->seed(ReceiptSeeder::class);

        $response = $this->receiptService->getTransactions();

        $this->assertTrue($response->isNotEmpty());
        $this->assertObjectHasProperty('period_by_date', $response->first());
        $this->assertObjectHasProperty('date', $response->first());
        $this->assertObjectHasProperty('items', $response->first());
    }
}
