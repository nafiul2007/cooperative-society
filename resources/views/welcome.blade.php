<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome - Cooperative Society</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">

    <div class="text-center p-4 bg-white rounded shadow" style="max-width: 400px; width: 100%;">
        <h1 class="mb-4">Welcome</h1>
        <x-application-logo class="me-3" style="height: auto; width: auto;" />
        <p class="mb-4 text-secondary">This is a cooperative society management system.</p>

        @if (Route::has('login'))
            <div class="d-flex justify-content-center gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" 
                                class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to logout?')">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-success">Log in</a>

                    {{-- @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                    @endif --}}
                @endauth
            </div>
        @endif
    </div>

    <!-- Bootstrap JS Bundle CDN (optional if you need JS features) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
