<?php

namespace App\Livewire\Item;

use App\Services\ItemStockService;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class BoxStockItem extends Component
{
    public function render()
    {
        return view('livewire.item.box-stock-item');
    }
}
