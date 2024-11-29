<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-colour : {{ session('site_primary_colour', config('app.default_primary_colour')) }};
            --secondary-colour : {{ session('site_secondary_colour', config('app.default_secondary_colour')) }};
        }
        .primary-colour { color: var(--primary-colour)  !important ;}
        .secondary-colour { color: var(--secondary-colour)  !important ;}
        .primary-background-colour { background-color: var(--primary-colour) !important ;}
        .secondary-background-colour { background-color: var(--secondary-colour) !important ;}
        .btn {
            background-color: var(--primary-colour) !important;
            border-radius: 25px;
            color: #fff !important;
            text-transform: unset !important;
            font-family: 'Soleil';
            font-size: 18px !important;
            line-height: 150%;
            padding: 12px 30px !important;
            height: 51px !important;
        }
        .btn-welcome {
            background-color: #004C3B !important;
            border-radius: 25px;
            color: #fff !important;
            text-transform: unset !important;
            font-family: 'Soleil';
            font-size: 18px !important;
            line-height: 150%;
            padding: 12px 30px !important;
            height: 51px !important;
        }
        .btn-outline {
            border-radius: 1000px;
            text-transform: unset !important;
            padding: 12px 30px !important;
            height: 51px !important;
        }
        .v-btn__content {
            text-transform: unset !important;
        }
        .v-label {
            margin-bottom: 0px
        }
    </style>
    <script src="https://js.stripe.com/v3/"></script>
    @if(config('app.env') === 'production')
        <script defer event-location="" data-domain="rememberalways.co.uk" src="https://plausible.io/js/script.manual.pageview-props.js"></script>
    @elseif(config('app.env') === 'local')
        <script defer event-location="{{ session('location') }}" data-domain="localhost" data-api="http://localhost:8000/api/event" src="https://plausible.io/js/script.local.manual.pageview-props.js"></script>
    @endif
</head>
<body>
    <div id="app">    
        <main>
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/glfx.js') }}"></script>
    <script src="{{ mix('js/gfxcompositemanager.js') }}"></script>
    <p style="text-align:center; font-size: 10px; color: #666666;" class="mb-0">{{ $version }}</p>
</body>
</html>
