<?php

namespace App\Livewire\Components;

use Livewire\Component;

class AlertDetail extends Component
{
    public $isHide = true;
    public $status;

    public function getListeners()
    {
        return [
            'do-show-box' => 'doShowBox'
        ];
    }

    public function doClose()
    {
        $this->isHide = true;

        session()->forget('alertDetailMessage');
    }

    public function doShowBox()
    {
        $this->isHide = false;
        $this->status = session()->get('alertDetailMessage')['status'];
    }

    public function render()
    {
        return view('livewire.components.alert-detail');
    }
}
