@extends('../layout/main-layout')

@section('main-section')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>Transaksi</h1>
            <ol class="breadcrumb">
                <li class="active"><a href="/master-item">Transaksi</a></li>
            </ol>
        </section>

        <section class="content">

            @livewire('receipt.box-transaction')

        </section>

    </div>
@endsection
