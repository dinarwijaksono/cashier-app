<?php

namespace App\Livewire\Item;

use App\Services\ItemStockService;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class BoxStockItem extends Component
{
    public $code;
    public $item;

    public function boot()
    {
        $itemStockService = App::make(ItemStockService::class);

        $this->item = $itemStockService->getItemStockByCode($this->code);
    }

    public function render()
    {
        return view('livewire.item.box-stock-item');
    }
}
