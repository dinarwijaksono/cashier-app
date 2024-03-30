<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">List item</h3>
    </div>

    <div class="box-body">

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-10">
                <div class="form-group ">
                    <input type="text" wire:model="name" id="name" class="form-control"
                        @error('name') style="border: 1px solid red;"  @enderror placeholder="Nama"
                        autocomplete="off" />
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group">
                    <button type="button" wire:click="doSearch" class="btn btn-success btn-block">Cari</button>
                </div>
            </div>
        </div>

        <hr />

        <table class="table table-border table-condensed table-striped" aria-describedby="table-show-items">
            <tr>
                <th>No</th>
                <th>
                    <button type="button" wire:click="doSort('created_at')" class="btn btn-block btn-sm text-bold">
                        Dibuat
                        @if ($sortBy == 'created_at')
                            @php
                                echo $sortIsDesc ? '(Naik)' : '(Turun)';
                            @endphp
                        @endif
                    </button>
                </th>
                <th>
                    <button type="button" wire:click="doSort('code')" class="btn btn-block btn-sm text-bold">
                        Code
                        @if ($sortBy == 'code')
                            @php
                                echo $sortIsDesc ? '(Naik)' : '(Turun)';
                            @endphp
                        @endif
                    </button>
                </th>
                <th>
                    <button type="button" wire:click="doSort('name')" class="btn btn-block btn-sm text-bold">Nama
                        @if ($sortBy == 'name')
                            @php
                                echo $sortIsDesc ? '(Naik)' : '(Turun)';
                            @endphp
                        @endif
                    </button>
                </th>
                <th>
                    <button type="button" wire:click="doSort('unit')" class="btn btn-block btn-sm text-bold">
                        Satuan
                        @if ($sortBy == 'unit')
                            @php
                                echo $sortIsDesc ? '(Naik)' : '(Turun)';
                            @endphp
                        @endif
                    </button>
                </th>
                <th>
                    <button type="button" wire:click="doSort('price')" class="btn btn-block btn-sm text-bold">
                        Harga
                        @if ($sortBy == 'price')
                            @php
                                echo $sortIsDesc ? '(Naik)' : '(Turun)';
                            @endphp
                        @endif
                    </button>
                </th>
                <th>
                    <button type="button" wire:click="doSort('stock')" class="btn btn-block btn-sm text-bold">
                        Stok
                        @if ($sortBy == 'stock')
                            @php
                                echo $sortIsDesc ? '(Naik)' : '(Turun)';
                            @endphp
                        @endif
                    </button>
                </th>
                <th colspan="3"></th>
            </tr>

            @foreach ($items as $key)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ date('h:i - d F Y', $key->created_at / 1000) }}</td>
                    <td class="text-center">{{ $key->code }}</td>
                    <td>{{ $key->name }}</td>
                    <td class="text-center">{{ $key->unit }}</td>
                    <td class="text-right">{{ 'Rp ' . number_format($key->price) }}</td>
                    <td class="text-center">{{ number_format($key->stock) }}</td>
                    <td>
                        <a href="/edit-item/{{ $key->code }}" class="btn btn-sm btn-success btn-block">Edit</a>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success btn-block">Tambah stok</button>
                    </td>

                    <td>
                        <button type="button" class="btn btn-sm btn-danger btn-block">Hapus</button>
                    </td>
                </tr>
            @endforeach

        </table>


    </div>
</div>
