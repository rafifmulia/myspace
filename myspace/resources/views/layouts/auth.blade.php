<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
    @stack('styles')
</head>
<body>

<div class="wrapper">
    <div class="container">
    @yield('content')
    </div>
</div>


<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>