<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Histori</h3>
    </div>

    <div class="box-body">

        <table class="table table-border" aria-describedby="table-list-change-history-item">
            <tr>
                <th>Diubah</th>
                <th>Nama sebelumnya</th>
                <th>Satuan sebelumnya</th>
                <th>Harga sebelumnya</th>
            </tr>

            @foreach ($itemChanges as $item)
                <tr>
                    <td>{{ date('h:i - d M Y', $item->created_at / 1000) }}</td>
                    <td>{{ $item->before_name }}</td>
                    <td>{{ $item->before_unit }}</td>
                    <td>{{ 'Rp ' . number_format($item->before_price) }}</td>
                </tr>
            @endforeach

        </table>

    </div>
</div>
