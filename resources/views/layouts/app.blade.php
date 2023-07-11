<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <!-- nouisliderribute css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/nouislider/nouislider.min.css') }}">

    <!-- Style -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <title>@yield('title') -  DigiDate </title>


</head>

<body>
    @php
         $nofic = App\Http\Controllers\HomeController::getNofic();
         $nofic_msg = App\Http\Controllers\HomeController::getNoficMsges();
    @endphp

    <nav class="navbar">
        <div class="container-fluid">
        <a class="navbar-brand" href="{{route('home')}}">DigiDate</a>
        <ul class="list-inline mt-4 text-success">
            @auth
                @switch(Auth::user()->role)
                    @case('user')
                        <li class="list-inline-item user-notifc">
                            <span class="badge badge-danger bg-danger">
                                {{ $nofic }}
                            </span>
                            <a href="{{ route('matching') }}">
                                <i class='bx bx-heart bx-tada bx-md'></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <span class="badge badge-danger bg-danger">
                                {{ $nofic_msg }}
                            </span>
                            <a href="{{ route('chat') }}"> <i class='bx bx-chat bx-md'></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a  href="{{ route('profileUser') }}"><i class='bx bx-cog bx-md'></i></a>
                        </li>
                        @break
                    @case('admin')
                        <li class="list-inline-item">
                            <a href="{{ route('dashboardAdmin') }}"><i class='bx bxs-dashboard bx-md'></i></i></a>
                        </li>
                        @break
                    @case('adminUser')
                        <li class="list-inline-item">
                            <a href="{{ route('matching') }}"> <i class='bx bx-heart bx-tada bx-md' ></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ route('chat') }}"> <i class='bx bx-chat bx-md'></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ route('dashboardAdmin') }}"><i class='bx bxs-dashboard bx-md'></i></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a  href="{{ route('profile') }}"><i class='bx bx-cog bx-md'></i></a>
                        </li>
                    @break
                @endswitch

                <li class="list-inline-item">
                    <a href="{{ route('logout') }}"> <i class='bx bx-log-out bx-md'></i></a>
                </li>
            @endauth
        </ul>
            <button class="btn btn-success">
                <a href="{{route('contact.create')}}" class="text-dark text-decoration-none"> Contact </a>
            </button>
        </div>
    </nav>

    <div class="container style-1 p-1 mb-3">
        @yield('content')
    </div>


    <footer class="footer mt-7">
        <div>
            <ul class="w-50 mx-auto">
                <li class="d-inline"><a href="{{ route('about-us') }}">Over ons</a></li>
                <li class="d-inline"><a href="{{ route('terms') }}">Voorwaarden </a></li>
            </ul>
        </div>
        <p class="text-center">Copyright@2022 DigiDate</p>
    </footer>
</body>
</html>
