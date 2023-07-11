<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <!-- Style -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('title', 'Dashboard') -  DigiDate </title>
</head>
<body>
    <div class="container main-wrapper">
        <div class="sidebar">
            <div class="logo_content">
                <a href="{{ route('home') }}" class="text-decoration-none">
                <div class="logo">
                    <div class="logo_name p-1">DigiDate</div>
                </div>
                </a>
                <i class='bx bx-menu' id="btn"></i>
            </div>
            <ul class="nav_list">
                <li>
                    <a href="{{ route('users.index') }}">
                        <i class='bx bx-user'></i>
                        <span class="links_name">Gebruikers</span>
                    </a>
                    <span class="tooltip">Gebruikers</span>
                </li>
                <li>
                    <a href="{{ route('contact.index') }}">
                        <i class='bx bx-chat'></i>
                        <span class="links_name">Berichten</span>
                    </a>
                    <span class="tooltip">Berichten</span>
                </li>
                <li>
                    <a href="{{ route('tags.index') }}">
                        <i class='bx bx-category'></i>
                        <span class="links_name">Tags</span>
                    </a>
                    <span class="tooltip">Tags</span>
                </li>
                <li>
                    <a href="{{ route('profile') }}">
                        <i class='bx bx-cog'></i>
                        <span class="links_name">Profile</span>
                    </a>
                    <span class="tooltip">Profile</span>
                </li>

                <li>
                    <a href="{{ route('switchTo') }}">
                        <i class='bx bxs-user-account'></i>
                        <span class="links_name">Switch gebruiker</span>
                    </a>
                    <span class="tooltip">Switch gebruiker</span>
                </li>

            </ul>
            <div class="content">
                <div class="user">
                    <div class="user_details">
    {{--                    <img src="#" alt="">--}}
                        <i class='bx bx-user'></i>
                        <div class="name_job">

                            @auth
                            <a href="#" class="text-light text-decoration-none">
                                <div class="name">{{ $user['user_info']['first_name']}}</div>
                                <div class="job p-1">Role: {{ strtoupper($user['role']) }}</div>
                            </a>
                            @endauth
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="get">
                        @csrf
                        <button type="submit"><i class='bx bx-log-out' id="log_out"></i></button>
                        </a>
                    </form>
                    </a>
                </div>
            </div>
        </div>
        <div class="home_content">
            <div class="text">Dashboard | @yield('title')</div>
            <div class="col-10 mx-auto">
                @if(session()->has('message'))
                    <div class="m-0 text-center alert alert-danger alert-dismissible fade show w-50 mx-auto" role="alert">
                        {{ session()->get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
        <script>

        </script>
    </body>

</body>
</html>
