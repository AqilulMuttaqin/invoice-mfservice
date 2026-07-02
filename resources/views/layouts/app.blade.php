<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('assets/src/img/icons/icon-48x48.png') }}" />

    <title>MF Service | </title>

    <link href="{{ asset('assets/src/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/src/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/src/css/dataTables.bootstrap5.css') }}" rel="stylesheet">
    @stack('style')
</head>

<body>
    <div class="wrapper">
        @include('layouts.sidebar')
        <div class="main">
            @include('layouts.navbar')
            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>
            @include('layouts.footer')
        </div>
    </div>

    <script src="{{ asset('assets/src/js/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('assets/src/js/app.js') }}"></script>
    <script src="{{ asset('assets/src/js/dataTables.js') }}"></script>
    <script src="{{ asset('assets/src/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/src/js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/src/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/src/js/chartjs-plugin-datalabels.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @stack('script')
</body>

</html>
