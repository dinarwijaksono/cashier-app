<?php

namespace App\Livewire\Item;

use App\Services\ItemStockService;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\On;
use Livewire\Component;

class BoxTableHistoryTransactions extends Component
{
    public $code;
    public $itemTransactions;

    public function boot()
    {
        $itemStockService = App::make(ItemStockService::class);
        $itemTransactions = collect($itemStockService->getItemTransactions($this->code));

        $this->itemTransactions = $itemTransactions->where('type', 'add_stock')->sortByDesc('date');
    }

    public function render()
    {
        return view('livewire.item.box-table-history-transactions');
    }
}
