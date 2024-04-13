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

            @livewire('components.alert-detail')

            <div class="row">

                <section class="col-sm-6">
                    @livewire('sales-transaction.form-add-item');
                </section>

                <section class="col-sm-6">
                    @livewire('sales-transaction.box-list-transaction')
                </section>

            </div>

        </section>

    </div>
@endsection
