<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shamazon</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif;
        }
    </style>

    @include('layouts.styles')
    @stack('styles')
</head>

<body>

    @include('layouts.header')
    @yield('content')


    @include('layouts.scripts')
    @stack('scripts')
</body>

</html>
