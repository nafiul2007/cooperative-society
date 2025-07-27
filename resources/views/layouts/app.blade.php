<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('header') - {{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card-header {
            font-weight: bold;
            background-color: #cecece;
            font-size: 1.1rem;
            /* Same as .h5 */
        }

        .card-header a {
            border: solid 1px #000000;
            padding: 3px 15px 5px 15px;
            border-radius: 5px;
            font-weight: normal;
            text-decoration: none;
        }

        .card-header a.edit-action {
            background-color: #ffc107;
            /* Bootstrap warning */
            color: #212529;
            /* Bootstrap warning text */
            border: 1px solid #ffc107;
        }

        .card-header a.edit-action:hover {
            background-color: #e0a800 !important;
            color: #212529;
            border-color: #d39e00;
        }

        .card-header a.back-action {
            background-color: #6c757d;
            /* Bootstrap secondary */
            color: #fff;
            /* White text */
            border: 1px solid #6c757d;
        }

        .card-header a.back-action:hover {
            background-color: #5c636a !important;
            border-color: #565e64;
            color: #fff;
        }

        .card-header a.add-person-action,
        .card-header a.add-action {
            background-color: #0d6efd;
            /* Bootstrap primary */
            color: #fff;
            /* primary text */
            border: 1px solid #0d6efd;
        }

        .card-header a.add-person-action:hover,
        .card-header a.add-action:hover {
            background-color: #0b5ed7 !important;
            border-color: #0a58ca;
            color: #fff;
        }

        #settingsDropdown .dropdown-menu .dropdown-item:hover,
        #settingsDropdown .dropdown-menu .dropdown-item:focus {
            background-color: #dadada;
            color: inherit; /* Optional: keeps default text color */
        }

    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid px-4 d-flex flex-column flex-lg-row align-items-center gap-3 gap-lg-0">

            <!-- Brand: full width on mobile, auto width on large -->
            <div class="d-flex flex-grow-1 flex-lg-grow-0 justify-content-center justify-content-lg-start">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <x-application-logo style="height: 30px;" class="me-3" />
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <!-- Clock: no view on mobile, fixed width and centered on large -->
            <div class="d-none d-lg-flex justify-content-center flex-grow-1">
                <div id="liveClock" class="text-light fs-5 text-center">
                    <div id="time" style="font-size: 0.8em;">--:--:--</div>
                    <div id="date" style="font-size: 0.8em;">-- -- ----</div>
                </div>
            </div>

            <!-- User menu: full width on mobile, auto width on large, aligned right on large -->
            @auth
                <div class="d-flex flex-grow-1 flex-lg-grow-0 justify-content-center justify-content-lg-end align-items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-light dashboard-action">Dashboard</a>

                    <div class="dropdown" id="settingsDropdown">
                        <button class="btn btn-outline-light dropdown-toggle settings-action" type="button" id="userMenu"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->displayName() }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item profile-action" href="{{ route('profile.edit') }}" style="">Edit Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-action" style="color: rgb(224, 18, 18); font-weight: bold !important;">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth

        </div>
    </nav>

    <main class="flex-grow-1" style="background-color: rgb(58, 58, 58) ;">
        <div class="p-4">
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
            const timeEl = document.getElementById('time');
            const dateEl = document.getElementById('date');
            if (!timeEl || !dateEl) return;

            const now = new Date();

            // Full day and full month format
            const time = now.toLocaleTimeString();
            const date = now.toLocaleDateString(undefined, {
                weekday: 'long', // Full day name
                day: 'numeric',
                month: 'long', // Full month name
                year: 'numeric'
            });

            timeEl.textContent = time;
            dateEl.textContent = date;
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const iconMap = {
                'edit-action': 'bi-pencil-square',
                'delete-action': 'bi-trash',
                'view-action': 'bi-eye',
                'back-action': 'bi-arrow-left-circle',
                'add-action': 'bi-plus-circle',
                'add-person-action': 'bi-person-plus',
                'download-action': 'bi-download',
                'print-action': 'bi-printer',
                'search-action': 'bi-search',
                'settings-action': 'bi-gear',
                'profile-action': 'bi-person-circle',
                'logout-action': 'bi-box-arrow-right',
                'dashboard-action': 'bi-house-door',
                'help-action': 'bi-question-circle',
                'notification-action': 'bi-bell',
                'report-action': 'bi-file-earmark-text',
                'share-action': 'bi-share',
                'archive-action': 'bi-archive',
                'favorite-action': 'bi-heart',
                'calendar-action': 'bi-calendar',
                'upload-action': 'bi-upload',
                'save-action': 'bi-save',
                'reset-action': 'bi-arrow-counterclockwise',
                'send-action': 'bi-envelope-check',
                'approve-action': 'bi-check-circle',
                'reject-action': 'bi-x-circle'

                // Add more mappings as needed
            };

            Object.entries(iconMap).forEach(([className, iconClass]) => {
                document.querySelectorAll(`.${className}`).forEach(el => {
                    const icon = document.createElement('i');
                    icon.className = `bi ${iconClass}`;
                    icon.style.marginRight = '6px';
                    el.prepend(icon);
                });
            });
        });
    </script>


</body>

</html>
