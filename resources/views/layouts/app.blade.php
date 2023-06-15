<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue-js-modal@1.3.31/dist/index.min.js"></script> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vue-js-modal@1.3.31/dist/styles.css"> 

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <renty-header :is-authorized="{{ var_export(Auth::check()) }}" :csrf="{{ json_encode(csrf_token()) }}"></renty-header>

        <main class="main">
            @yield('content')
        </main>

        <renty-footer :is-authorized="{{ var_export(Auth::check()) }}" active-button="@yield('activeFooter')"></renty-footer>
    </div>
</body>
</html>
