<?php

namespace App\Livewire\Item;

use App\Services\ItemService;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class BoxTableListItem extends Component
{
    public $items;
    public $sortBy;
    public $sortIsDesc;

    public $name;

    protected $itemService;

    public function getRules()
    {
        return [
            'name' => 'required'
        ];
    }

    public function boot()
    {
        $this->itemService = App::make(ItemService::class);
    }

    public function mount()
    {
        $this->sortBy = 'code';
        $this->sortIsDesc = false;

        $this->items = $this->itemService->getAll()->sortBy($this->sortBy);
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
    }

    public function doSearch()
    {
        $this->validate();

        $this->items = $this->itemService->getByName($this->name);
    }

    public function render()
    {
        return view('livewire.item.box-table-list-item');
    }
}
