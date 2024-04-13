<?php

namespace App\Livewire\SalesTransaction;

use Livewire\Component;

class BoxListTransaction extends Component
{
    public $transactions;

    public function getListeners()
    {
        return [
            'add-item' => 'boot',
            'cencel-transactions' => 'boot'
        ];
    }

    public function boot()
    {
        $this->transactions = collect([]);

        if (session()->has('transactions')) {
            $this->transactions = collect(session()->get('transactions'));
        }
    }

    public function doCencelTransaction()
    {
        session()->forget('transactions');

        $this->dispatch('cencel-transactions')->self();
    }

    public function render()
    {
        return view('livewire.sales-transaction.box-list-transaction');
    }
}
