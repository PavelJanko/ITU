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
            @yield('layout')
        </div>

        @section('scripts')
            {{-- Scripts --}}
            <script src="/js/app.js"></script>
            <script>
                @if(session('statusType') && session('statusTitle') && session('statusText'))
                    swal('{{ session('statusTitle') }}', '{{ session('statusText') }}', '{{ session('statusType') }}');
                @endif

                var triggerModalFocus = function(modalId, inputId) {
                    $(modalId).on('shown.bs.modal', function() {
                        $(inputId).trigger('focus');
                    });
                };
            </script>
        @show
    </body>
</html>