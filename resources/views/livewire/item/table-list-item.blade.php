<div class="box box-success">
    <div class="box-body ">
        <table class="table table-border" aria-describedby="table-show-item">

            <tr>
                <th>Tanggal</th>
                <th>Code</th>
                <th>Nama Item</th>
                <th>Satuan</th>
                <th>Harga</th>
            </tr>

            @foreach ($listItem as $item)
                <tr>
                    <td>{{ date('H:i - d M Y', $item->created_at / 1000) }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ 'Rp ' . number_format($item->price) }}</td>
                </tr>
            @endforeach

        </table>
    </div>
</div>
