<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="/adminLTE/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="/adminLTE/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="/adminLTE/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    @livewireStyles

    <title>{{ env('APP_NAME') }}</title>
</head>

<body class="skin-green">

    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="/" class="logo">Cashier<b>App</b></a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
            </nav>
        </header>

        @livewire('components.sidebar')

        <!-- Right side column. Contains the navbar and content of the page -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>Tambah item</h1>
                <ol class="breadcrumb">
                    <li><a href="/master-item">Master Item</a></li>
                    <li class="active">Add Item</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">

                @livewire('components.alert')

                @livewire('item.form-add-item')

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

                            @for ($i = 0; $i < 20; $i++)
                                <tr>
                                    <td>12 Maret 2023</td>
                                    <td>I00001</td>
                                    <td>Pulpen</td>
                                    <td>pcs</td>
                                    <td>Rp 3.000</td>
                                </tr>
                            @endfor

                        </table>
                    </div>
                </div>

            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> {{ env('APP_VERSION') }}
            </div>
            <strong>Cashier-app </strong>
        </footer>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.3 -->
    <script src="/adminLTE/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="/adminLTE/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- SlimScroll -->
    <script src="/adminLTE/plugins/slimScroll/jquery.slimScroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='/adminLTE/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="/adminLTE/dist/js/app.min.js" type="text/javascript"></script>

    @livewireScripts
</body>

</html>