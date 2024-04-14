<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Services\SalesTransactionService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceiptSeeder extends Seeder
{
    public $salesTransactionService;

    public function __construct(SalesTransactionService $salesTransactionService)
    {
        $this->salesTransactionService = $salesTransactionService;
    }

    public function run(): void
    {
        $item = Item::select('*')->first();
        $item2 = Item::select('*')->where('id', '!=', $item->id)->first();

        $this->salesTransactionService->addItem($item->code, 5);
        $this->salesTransactionService->addItem($item2->code, 7);
        $this->salesTransactionService->processTransaction(session()->get('transactions'));
    }
}
