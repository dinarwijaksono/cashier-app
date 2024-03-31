<?php

namespace App\Livewire\Item;

use App\Livewire\Components\Alert;
use App\Services\ItemService;
use App\Services\ItemStockService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BoxAddStock extends Component
{
    public $code;
    public $date;
    public $qty;

    public function mount()
    {
        $this->date = date('Y-m-y');
        $this->qty = 0;
    }

    public function boot()
    {
        Log::withContext(['class' => BoxAddStock::class]);
    }

    public function getRules()
    {
        return [
            'date' => 'required',
            'qty' => 'required|numeric|min:0.05'
        ];
    }

    public function doAddStock()
    {
        $this->validate();

        try {
            $itemStockService = App::make(ItemStockService::class);
            $itemService = App::make(ItemService::class);
            $item = $itemService->getByCode($this->code);

            $date = strtotime($this->date) * 1000;

            $itemStockService->addition($item->id, $date, $this->qty);

            $this->date = date('Y-m-y');
            $this->qty = 0;

            session()->put('alertMessage', "Stock berhasil di tambahkan.");

            $this->dispatch('do-show-box')->to(Alert::class);

            Log::info('do add stock success');
        } catch (\Throwable $th) {
            Log::error('do add stock failed', [
                'message' => $th->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.item.box-add-stock');
    }
}
