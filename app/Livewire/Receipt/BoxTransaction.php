<?php

namespace App\Livewire\Receipt;

use App\Services\ReceiptService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BoxTransaction extends Component
{
    public $transactions;

    public function boot()
    {
        Log::withContext(['class' => BoxTransaction::class]);

        $receiptService = App::make(ReceiptService::class);
        $transactions = $receiptService->getTransactions();
        $this->transactions = $transactions->sortByDesc('date');
    }

    public function render()
    {
        return view('livewire.receipt.box-transaction');
    }
}
