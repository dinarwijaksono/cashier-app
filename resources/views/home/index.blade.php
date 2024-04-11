@extends('../layout/main-layout')

@section('main-section')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>Dashboard</h1>
            <ol class="breadcrumb">
                <li class="active"><a href="/master-item">Dashboard</a></li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                <section class="col-sm-6">
                    @livewire('sales-transaction.form-add-item');
                </section>

                <section class="col-sm-6">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Daftar transaksi</h3>
                        </div>

                        <div class="box-body">

                            <p class="text-right">12:33 - 23 Maret 2024</p>

                            <table class="table table-bordered" aria-describedby="table-list">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Deskripsi</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center"></th>
                                </tr>

                                @for ($i = 0; $i < 3; $i++)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>Lorem ipsum dolor sit amet.</td>
                                        <td class="text-center">2 kg</td>
                                        <td class="text-right">10.000</td>
                                        <td class="text-right">Rp 20.000</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger btn-block">Hapus</button>
                                        </td>
                                    </tr>
                                @endfor
                                <tr>
                                    <td class="text-bold text-right" colspan="4">Total</td>
                                    <td class="text-bold text-success text-right">Rp 100.000.000</td>
                                </tr>
                            </table>
                        </div>

                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4">
                                    <button class="btn btn-block btn-sm btn-danger">Batal</button>
                                </div>

                                <div class="col-sm-4"></div>

                                <div class="col-sm-4">
                                    <button class="btn btn-sm btn-block btn-success">Proses</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>

        </section>

    </div>
@endsection
