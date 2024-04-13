<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Tambah Item</h3>
    </div>

    <div class="box-body">

        <div class="form-group">
            <label for="name">Nama Item</label>
            <input type="text" wire:model="name" list="item-list" class="form-control" id="name" autocomplete="off"
                placeholder="Nama" autofocus>
            @error('name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <datalist id="item-list">
            @foreach ($items as $key)
                <option value="{{ $key->name }}">{{ $key->name }}</option>
            @endforeach
        </datalist>

        <div class="form-group">
            <label for="qty">Qty</label>
            <input type="text" wire:model="qty" class="form-control" id="qty" placeholder="Qty">
            @error('qty')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="row">
            <div class="col-sm-8"></div>
            <div class="col-sm-4">
                <button type="button" wire:click="doAddItem" class="btn btn-block btn-success btn-sm">Tambah
                    item</button>
            </div>
        </div>

    </div>
</div>
