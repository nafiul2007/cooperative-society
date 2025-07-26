<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <x-application-logo style="height: 30px;" class="me-3" />
                {{ config('app.name', 'Laravel') }}
            </a>
            <!-- Clock goes here -->
            <div id="liveClock" class="text-light fs-5 mx-auto"
                style="position: absolute; left: 50%; transform: translateX(-50%);">
                --:--:--
            </div>
            @auth
                <div class="ms-auto d-flex align-items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-light">Dashboard</a>

                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" id="userMenu"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->displayName() }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    <main class="flex-grow-1">
        <div class="container py-4">
            @isset($header)
                <div class="mb-4">
                    <h2 class="h4">{{ $header }}</h2>
                </div>
            @endisset

            <div class="row g-3">
                @auth

                    <div class="col-md-3">
                        <div class="card h-100">
                            @include('layouts.navigation')
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card h-100">
                            @yield('content')
                        </div>
                    </div>
                @else
                    @yield('content')
                @endauth
            </div>
        </div>
    </main>

    <footer class="bg-dark text-light py-3 mt-auto">
        <div class="container text-center">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </div>
    </footer>

    @stack('scripts')
    <script>
        function updateClock() {
            const clock = document.getElementById('liveClock');
            if (!clock) return;

            const now = new Date();
            clock.textContent = now.toLocaleTimeString();
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
</body>

</html>
