<?php

namespace App\Livewire\Item;

use App\Services\ItemStockService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class BoxTableHistoryTransactions extends Component
{
    public $code;
    protected $itemStockService;
    public $itemTransactions;

    public function boot()
    {
        Log::withContext(['class' => BoxTableHistoryTransactions::class]);

        $this->itemStockService = App::make(ItemStockService::class);
        $itemTransactions = collect($this->itemStockService->getItemTransactions($this->code));

        $this->itemTransactions = $itemTransactions->where('type', 'add_stock')->sortByDesc('date');
    }

    public function doDeleteTransaction(int $transactionId)
    {
        try {

            $this->itemStockService->deleteTransaction($transactionId);

            session()->flash('alertMessage', "Transaksi berhasil di batalkan.");

            return redirect("/add-stock/$this->code");

            Log::info('do delete transaction success');
        } catch (\Throwable $th) {
            Log::error('do delete transaction failed', [
                'message' => $th->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.item.box-table-history-transactions');
    }
}
