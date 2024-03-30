<?php

namespace App\Livewire\Item;

use App\Domain\ItemDomain;
use App\Livewire\Components\Alert;
use App\Services\ItemService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

use App\Livewire\Item\BoxTableChangeItemHistory;

class FormEditItem extends Component
{
    public $code;
    public $itemName;
    public $unit;
    public $price;

    public $item;

    protected $itemService;

    public function boot()
    {
        Log::withContext([
            'class' => FormEditItem::class
        ]);

        $this->itemService = App::make(ItemService::class);

        $this->item = $this->itemService->getByCode($this->code);
    }

    public function mount()
    {
        $this->itemName = $this->item->name;
        $this->unit = $this->item->unit;
        $this->price = $this->item->price;
    }


    public function getRules()
    {
        return [
            'itemName' => 'required',
            'unit' => 'required',
            'price' => 'required|Min:1|numeric'
        ];
    }

    public function doEditItem()
    {
        $this->validate();

        try {

            $itemDomain = new ItemDomain();
            $itemDomain->code = $this->code;
            $itemDomain->name = $this->itemName;
            $itemDomain->unit = $this->unit;
            $itemDomain->price = $this->price;

            $this->itemService->updateByCode($itemDomain);

            session()->put('alertMessage', "Item berhasil di edit.");

            $this->dispatch('do-show-box')->to(Alert::class);

            Log::info('do edit item success');
        } catch (\Throwable $th) {
            Log::error('do edit item failed', [
                'message' => $th->getMessage()
            ]);
        }
    }


    public function render()
    {
        return view('livewire.item.form-edit-item');
    }
}
