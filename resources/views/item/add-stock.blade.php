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

            @if (session()->has('alertMessage'))
                <div class="alert alert-warning">
                    <div class="row">
                        <div class="col-sm-11">
                            @if (session()->has('alertMessage'))
                                <h4>{{ session()->get('alertMessage') }}</h4>
                            @endif
                        </div>
                    </div>
                </div>
            @enderror


            <div class="row">

                <div class="col-sm-6">
                    @livewire('item.box-add-stock', ['code' => $code])
                </div>

                <div class="col-sm-6">
                    @livewire('item.box-stock-item', ['code' => $code])
                </div>

            </div>

            @livewire('item.box-table-history-transactions', ['code' => $code])

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
@endsection
