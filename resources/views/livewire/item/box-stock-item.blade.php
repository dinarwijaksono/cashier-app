<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title" style="margin-bottom: 20px;">Stock Item</h3>
        <p>Nama : {{ $item->name }} <br> Periode : {{ $item->period }} <br> Satuan : {{ $item->unit }}</p>
    </div>

    <div class="box-body">

        <table class="table table-border" aria-describedby="tabel-stock-item">

            <tr>
                <th class="text-center">Stock Awal</th>
                <th class="text-center">Adjusment</th>
                <th class="text-center">Stock Akhir</th>
            </tr>

            <tr>
                <td class="text-center">{{ number_format($item->first_stock, 2) }}</td>
                <td class="text-center">{{ number_format($item->adjusment, 2) }}</td>
                <td class="text-center">{{ number_format($item->last_stock, 2) }}</td>
            </tr>

        </table>

    </div>
</div>
