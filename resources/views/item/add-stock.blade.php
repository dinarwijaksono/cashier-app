@extends('../layout/main-layout')

@section('main-section')
    <!-- Right side column. Contains the navbar and content of the page -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Tambah Stock Item</h1>
            <ol class="breadcrumb">
                <li><a href="/master-item">Master Item</a></li>
                <li class="active">Add stock</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            @livewire('components.alert')

            <div class="row">

                <div class="col-sm-6">
                    @livewire('item.box-add-stock', ['code' => $code])
                </div>

                <div class="col-sm-6">
                    @livewire('item.box-stock-item', ['code' => $code])
                </div>

            </div>

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

                        <tr>
                            <td class="text-center">23:31 - 12 Maret 2024</td>
                            <td class="text-center">10</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger btn-block">Batalkan</button>
                            </td>
                        </tr>

                    </table>

                </div>
            </div>


        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection
