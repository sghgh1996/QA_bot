<!doctype html>
<html lang="fa" dir="rtl">

<head>
    <title>پرسش و پاسخ سوالات فارسی</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Material Kit CSS -->
    <link href="{{ URL::asset('material-dashboard-assets/css/material-dashboard-rtl.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('material-dashboard-assets/css/material-dashboard.css') }}" rel="stylesheet" />
</head>

<body>
    <div class="wrapper">
        <div
            class="sidebar"
            data-color="azure"
            data-background-color="white"
        >
            <div class="logo">
                <a href="#" class="simple-text logo-normal">
                    سیستم پرسش و پاسخ<br/>
                    سوالات فارسی
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ url('dashboard') }}">
                        <i class="material-icons">dashboard</i>
                        <p>نتایج</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                    <a class="navbar-brand" href="{{ url('dashboard') }}">داشبورد</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content bg-white">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="copyright text-center">
                        طراحی و پیاده سازی با <i class="material-icons">favorite</i> توسط سجاد قنبری نسب
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>
