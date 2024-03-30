<?php

namespace App\Livewire\Item;

use App\Services\ItemService;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class BoxTableChangeItemHistory extends Component
{
    public $code;

    public $itemChanges;

    public function boot()
    {
        $itemService = App::make(ItemService::class);

        $item = $itemService->getBycode($this->code);

        $this->itemChanges = $itemService->getItemChangeHistoryByItemId($item->id);
    }

    public function render()
    {
        return view('livewire.item.box-table-change-item-history');
    }
}
