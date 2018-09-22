<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="img/favicon.png">
        <title>{{ config('app.name') }}</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{{ asset('vendor/paybox/css/paybox.css') }}">
    </head>

    <body>
        <div id="app">

            @section('header')
                @include('paybox::partials._header')
            @show

            @yield('content')

        </div>

        <script src="{{ asset('vendor/paybox/js/uikit.min.js') }}"></script>
        <script src="{{ asset('vendor/paybox/js/paybox-admin.js') }}"></script>
        @yield('scripts')
    </body>
</html>
