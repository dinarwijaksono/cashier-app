<?php

namespace App\Livewire\SalesTransaction;

use App\Livewire\Components\AlertDetail;
use App\Models\Item;
use App\Services\ItemService;
use App\Services\SalesTransactionService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Livewire\SalesTransaction\BoxListTransaction;
use Livewire\Component;

class FormAddItem extends Component
{
    public $items;

    public $name;
    public $qty;

    protected $itemService;

    public function mount()
    {
        Log::withContext(['class' => FormAddItem::class]);

        $this->qty = 1;
    }

    public function boot()
    {
        $this->itemService = App::make(ItemService::class);
        $this->items = $this->itemService->getAll();
    }

    public function doAddItem()
    {
        try {
            $salesTransactionServiec = App::make(SalesTransactionService::class);

            $item = Item::select('price', 'unit', 'code')->where('name', $this->name)->first();

            $salesTransactionServiec->addItem($item->code, $this->qty);

            $this->name = '';
            $this->qty = 1;

            session()->put('alertDetailMessage', [
                'message' => 'Item berhasil di tambahkan.',
                'status' => 'success'
            ]);

            $this->dispatch('add-item')->to(BoxListTransaction::class);
            $this->dispatch('do-show-box')->to(AlertDetail::class);

            Log::info('do add item success');
        } catch (\Throwable $th) {
            Log::error('do add item failed', [
                'message' => $th->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.sales-transaction.form-add-item');
    }
}
