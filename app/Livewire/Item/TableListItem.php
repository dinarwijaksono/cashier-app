<?php

namespace App\Livewire\Item;

use App\Services\ItemService;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class TableListItem extends Component
{
    public $listItem;

    public function getListeners()
    {
        return [
            'add-item' => 'render'
        ];
    }

    public function boot()
    {
        $itemService = App::make(ItemService::class);

        $this->listItem = $itemService->getAll()
            ->sortByDesc('created_at');
    }

    public function render()
    {
        return view('livewire.item.table-list-item');
    }
}
