<?php

namespace App\Livewire\Item;

use App\Livewire\Components\Alert;
use App\Services\ItemService;
use App\Services\ItemStockService;
use App\Services\ItemTransactionService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BoxTableListItem extends Component
{
    public $items;
    public $sortBy;
    public $sortIsDesc;
    public $transactions;

    public $name;

    protected $itemService;
    protected $itemStockService;
    protected $itemTransactionService;

    public function getRules()
    {
        return [
            'name' => 'required'
        ];
    }

    public function boot()
    {
        $this->itemService = App::make(ItemService::class);
        $this->itemStockService = App::make(ItemStockService::class);
        $this->itemTransactionService = App::make(ItemTransactionService::class);
    }

    public function mount()
    {
        Log::withContext(['class' => BoxTableListItem::class]);

        $this->sortBy = 'code';
        $this->sortIsDesc = false;

        $this->items = $this->itemService->getAll()->sortBy($this->sortBy);
        $this->transactions = $this->itemTransactionService->getTransactions();
    }

    public function doSort($key)
    {
        if ($this->sortBy == $key) {

            $this->sortIsDesc = !$this->sortIsDesc;

            if ($this->sortIsDesc) {
                $this->items = $this->items->sortByDesc($this->sortBy);
            } else {
                $this->items = $this->items->sortBy($this->sortBy);
            }
        } else {
            $this->sortBy = $key;
            $this->sortIsDesc = false;
            $this->items = $this->items->sortBy($this->sortBy);
        }

        Log::info('do sort success');
    }

    public function doSearch()
    {
        $this->validate();

        $this->items = $this->itemService->getByName($this->name);

        Log::info('do search success');
    }

    public function doDeleteItem(int $itemId)
    {

        $this->itemStockService->deleteItem($itemId);

        session()->put('alertMessage', "Item Berhasil di hapus.");

        $this->dispatch('do-show-box')->to(Alert::class);

        Log::info('do delete item success');
    }

    public function render()
    {
        return view('livewire.item.box-table-list-item');
    }
}
