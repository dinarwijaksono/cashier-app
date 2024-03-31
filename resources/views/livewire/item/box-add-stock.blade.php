<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Tambah Stock</h3>
    </div>

    <div class="box-body">
        <div class="form-group">
            <label for="date">Tanggal</label>
            <input type="date" wire:model="date" class="form-control" id="date" placeholder="Tanggal"
                autocomplete="off" autofocus>
            @error('date')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="qty">Qty</label>
            <input type="text" wire:model="qty" class="form-control" id="qty" placeholder="off"
                autocomplete="off">
            @error('qty')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="row">
            <div class="col-sm-8"></div>
            <div class="col-sm-4">
                <button type="button" wire:click="doAddStock" class="btn btn-sm btn-success btn-block">Simpan</button>
            </div>
        </div>

    </div>
</div>
