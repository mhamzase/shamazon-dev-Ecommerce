<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shamazon</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('layouts.styles')
    @stack('styles')
</head>

<body>

    @include('vender.layouts.header')
    @yield('content')



    @include('layouts.scripts')
    @stack('scripts')
</body>

</html>
