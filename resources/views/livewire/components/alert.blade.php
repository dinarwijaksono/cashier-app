<div class="alert alert-warning" @style(['display: none' => $boxHidden])>
    <div class="row">
        <div class="col-sm-11">
            @if (session()->has('alertMessage'))
                <h4>{{ session()->get('alertMessage') }}</h4>
            @endif
        </div>
        <div class="col-sm-1">
            <button type="button" wire:click="doHideBox" class="btn btn-default btn-sm btn-block">Tutup</button>
        </div>
    </div>
</div>
