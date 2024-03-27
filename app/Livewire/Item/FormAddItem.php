<?php

namespace App\Livewire\Item;

use App\Livewire\Components\Alert;
use App\Services\ItemService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class FormAddItem extends Component
{
    public $itemName;
    public $unit;
    public $price;

    public function rules()
    {
        return [
            'itemName' => 'required',
            'unit' => 'required',
            'price' => 'required|numeric|min:1'
        ];
    }

    public function boot()
    {
        Log::withContext(['class' => FormAddItem::class]);
    }

    public function doAddItem()
    {
        $this->validate();

        try {

            $itemService = App::make(ItemService::class);
            $itemService->create($this->itemName, $this->unit, $this->price);

            session()->flash('inputSuccess', 'Item berhasil di tambahkan.');

            $this->itemName = '';
            $this->unit = '';
            $this->price = '';

            session()->put('alertMessage', 'Item berhasil ditambahkan.');

            $this->dispatch('do-show-box')->to(Alert::class);
            $this->dispatch('add-item')->to(TableListItem::class);

            Log::info('do add item success');
        } catch (\Throwable $th) {
            Log::error('do add item failed', [
                'exeption' => $th->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.item.form-add-item');
    }
}
