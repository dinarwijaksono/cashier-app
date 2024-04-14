<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Transaksi History</h3>
    </div>

    <div class="box-body">

        @foreach ($transactions as $key)
            <section style="border: 1px solid black; padding: 15px; margin-bottom: 10px;">
                <p class="text-right"><u>{{ date('h:i - d F Y', $key->date / 1000) }}</u></p>

                <table class="table table-border table-bordered" aria-describedby="table-transactions">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Nama</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Total</th>
                    </tr>

                    @foreach ($key->items as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-center">{{ number_format($item->qty) }}</td>
                            <td class="text-right">{{ 'Rp ' . number_format($item->price) }}</td>
                            <td class="text-right">{{ 'Rp ' . number_format($item->total) }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="text-bold text-success text-right" colspan="4">Grand Total</td>
                        <td class="text-bold text-success text-right">{{ 'Rp ' . number_format($key->grand_total) }}
                        </td>
                    </tr>

                </table>

                <div class="row">
                    <div class="col-sm-9"></div>
                    <div class="col-sm-3">
                        <button class="btn btn-default btn-sm btn-block">Cetak ulang</button>
                    </div>
                </div>

            </section>
        @endforeach

    </div>
</div>
