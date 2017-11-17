<!DOCTYPE html>
<html lang="cs">
    <head>
        {{-- Meta tags --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Page title --}}
        <title>{{ config('app.name') }}{{ isset($pageTitle) ? ' &middot; ' . $pageTitle : '' }}</title>

        {{-- CSS --}}
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
        <div id="app">
            @include('partials.navbar-top')
            @yield('content')
        </div>

        {{-- Scripts --}}
        <script src="/js/app.js"></script>
        @yield('scripts')
    </body>
</html>