<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Alert extends Component
{
    public $boxHidden = true;

    public function getListeners()
    {
        return [
            'do-hide-box' => 'doHideBox',
            'do-show-box' => 'doShowBox',
        ];
    }

    public function doHideBox()
    {
        session()->forget('alertMessage');

        $this->boxHidden = true;
    }

    public function doShowBox()
    {
        $this->boxHidden = false;
    }

    public function render()
    {
        return view('livewire.components.alert');
    }
}
