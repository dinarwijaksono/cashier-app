<div @class([
    'alert',
    'alert-success' => $status == 'success',
    'alert-danger' => $status == 'danger',
    'alert-info' => $status == 'info',
    'alert-warning' => $status == 'warning',
]) @style(['display: none;' => $isHide])>
    <div class="row">
        <div class="col-sm-11">
            @if (session()->has('alertDetailMessage'))
                <b>
                    <p>{{ session()->get('alertDetailMessage')['message'] }}</p>
                </b>
            @endif
        </div>
        <div class="col-sm-1">
            <button type="button" wire:click="doClose" class="btn btn-sm btn-default">Hapus</button>
        </div>
    </div>
</div>
