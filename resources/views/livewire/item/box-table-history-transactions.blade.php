<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">History penambahan stock</h3>
    </div>

    <div class="box-body">

        <table class="table table-border" aria-describedby="table-list-item">
            <tr>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Qty</th>
                <th class="text-center"></th>
            </tr>

            @foreach ($itemTransactions as $item)
                <tr>
                    <td class="text-center">{{ date('d F Y', $item->date / 1000) }}</td>
                    <td class="text-center">{{ number_format($item->qty_in, 2) }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger btn-block">Batalkan</button>
                    </td>
                </tr>
            @endforeach


        </table>

    </div>
</div>
