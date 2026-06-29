<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('assets/src/img/icon.png') }}" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-in.html" />

    <title>MF Service | Login</title>

    <link href="{{ asset('assets/src/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/src/css/css2.css') }}" rel="stylesheet">
</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <div class="text-center mt-4">
                            <a href="/login" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                <img src="{{ asset('assets/src/img/icons/icon-48x48.png') }}" width="80" alt="">
                            </a>
                            <p class="lead">Invoice System MF Service</p>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/src/js/app.js') }}"></script>
</body>

</html>
