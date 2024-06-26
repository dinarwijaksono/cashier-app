<?php

namespace App\Livewire\SalesTransaction;

use App\Livewire\Components\AlertDetail;
use App\Services\SalesTransactionService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BoxListTransaction extends Component
{
    public $transactions;

    protected $salesTransactionService;

    public function getListeners()
    {
        return [
            'add-item' => 'boot',
            'change-transactions' => 'boot',

        ];
    }

    public function boot()
    {
        Log::withContext(['class' => BoxListTransaction::class]);

        $this->salesTransactionService = App::make(SalesTransactionService::class);

        $this->transactions = collect([]);

        if (session()->has('transactions')) {
            $this->transactions = collect(session()->get('transactions'));
        }
    }

    public function doCencelTransaction()
    {
        session()->forget('transactions');

        session()->put('alertDetailMessage', [
            'message' => 'Transaksi berhasil di batalkan.',
            'status' => 'warning'
        ]);

        $this->dispatch('change-transactions')->self();
        $this->dispatch('do-show-box')->to(AlertDetail::class);
    }

    public function doDeleteItem(string $code)
    {
        try {

            $this->salesTransactionService->deleteItem($code);

            session()->put('alertDetailMessage', [
                'message' => 'Item berhasil di hapus.',
                'status' => 'warning'
            ]);

            $this->dispatch('change-transactions')->self();
            $this->dispatch('do-show-box')->to(AlertDetail::class);

            Log::info('do delete item success');
        } catch (\Throwable $th) {
            Log::error('do delete item failed', [
                'message' => $th->getMessage()
            ]);
        }
    }

    public function doProcess()
    {
        try {
            if (!session()->has('transactions')) {

                session()->put('alertDetailMessage', [
                    'message' => 'Transaksi tidak bisa di proses, karena transaksi kosong.',
                    'status' => 'danger'
                ]);

                $this->dispatch('do-show-box')->to(AlertDetail::class);
                return;
            }

            $this->salesTransactionService->processTransaction(session()->get('transactions'));

            $this->transactions = collect([]);

            session()->put('alertDetailMessage', [
                'message' => 'Transaksi berhasil di proses.',
                'status' => 'success'
            ]);

            $this->dispatch('change-transactions')->self();
            $this->dispatch('do-show-box')->to(AlertDetail::class);

            Log::info('do process success');
        } catch (\Throwable $th) {
            Log::error('do process failed', [
                'message' => $th->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.sales-transaction.box-list-transaction');
    }
}
