<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Evergreen Merchant Community') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles (Optional) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
        <div class="card shadow border-0" style="width: 100%; max-width: 420px;">
            <div class="card-body">
                <div class="text-center mb-4">
                    <a href="/">
                        <img src="{{ asset('logo.png') }}" alt="Logo" height="64">
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
