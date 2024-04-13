<?php

namespace App\Livewire\SalesTransaction;

use App\Services\SalesTransactionService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BoxListTransaction extends Component
{
    public $transactions;

    public function getListeners()
    {
        return [
            'add-item' => 'boot',
            'change-transactions' => 'boot',

        ];
    }

    public function boot()
    {
        Log::withContext(['class' => BoxListTransaction::class]);

        $this->transactions = collect([]);

        if (session()->has('transactions')) {
            $this->transactions = collect(session()->get('transactions'));
        }
    }

    public function doCencelTransaction()
    {
        session()->forget('transactions');

        $this->dispatch('change-transactions')->self();
    }

    public function doDeleteItem(string $code)
    {
        try {

            $salesTransactionService = App::make(SalesTransactionService::class);

            $salesTransactionService->deleteItem($code);

            $this->dispatch('change-transactions')->self();

            Log::info('do delete item success');
        } catch (\Throwable $th) {
            Log::error('do delete item failed', [
                'message' => $th->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.sales-transaction.box-list-transaction');
    }
}
