<div class="box box-success">

    <div class="box-body">

        <div class="form-group">
            <label for="itemName">Nama item</label>
            <input type="text" id="itemName" wire:model="itemName" class="form-control" autocomplete="off"
                placeholder="Nama Item">
            @error('itemName')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="unit">Satuan</label>
            <select id="unit" wire:model="unit" class="form-control">
                <option>-- pilih --</option>
                <option value="kg">kg</option>
                <option value="pcs">pcs</option>
                <option value="liter">liter</option>
            </select>

            @error('unit')
                <p class="text-danger">{{ $message }}</p>
            @enderror

        </div>

        <div class="form-group">
            <label for="itemName">Harga</label>
            <input type="text" wire:model="price" id="price" class="form-control" autocomplete="off"
                placeholder="Harga">
            @error('price')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="row">
            <div class="col-sm-9"></div>
            <div class="col-sm-3">
                <button type="button" wire:click="doAddItem" class="btn btn-sm btn-success btn-block">Buat
                    item</button>
            </div>
        </div>

    </div>
</div>
