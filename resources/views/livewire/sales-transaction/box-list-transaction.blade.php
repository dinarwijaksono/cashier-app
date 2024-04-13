<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Daftar transaksi</h3>
    </div>

    <div class="box-body">

        <p class="text-right"><u>{{ date('H:i - d F Y') }}</u></p>

        <table class="table table-bordered" aria-describedby="table-list">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Deskripsi</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Total</th>
                <th class="text-center"></th>
            </tr>

            @php $grandTotal = 0; @endphp

            @foreach ($transactions as $key)
                @php $grandTotal += $key['total'] @endphp

                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $key['name'] }}</td>
                    <td class="text-center">{{ number_format($key['qty']) . ' ' . $key['unit'] }}</td>
                    <td class="text-right">{{ number_format($key['price']) }}</td>
                    <td class="text-right">{{ 'Rp ' . number_format($key['total']) }}</td>
                    <td>
                        <button type="button" wire:click="doDeleteItem('{{ $key['code'] }}')"
                            class="btn btn-sm btn-danger btn-block">Hapus</button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="text-bold text-right" colspan="4">Total</td>
                <td class="text-bold text-success text-right">{{ 'Rp ' . number_format($grandTotal) }}</td>
            </tr>
        </table>
    </div>

    <div class="box-footer">
        <div class="row">
            <div class="col-sm-4">
                <button type="button" wire:click="doCencelTransaction"
                    class="btn btn-block btn-sm btn-danger">Batal</button>
            </div>

            <div class="col-sm-4"></div>

            <div class="col-sm-4">
                <button class="btn btn-sm btn-block btn-success">Proses</button>
            </div>
        </div>
    </div>
</div>
