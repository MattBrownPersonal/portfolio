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
    <link href="{{ mix('css/admin.css') }}" rel="stylesheet">
    <script src="{{ mix('js/glfx.js') }}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.6.0/Sortable.js"></script>

    <style>
        :root {
            --primary-colour : {{ session('site_primary_colour', config('app.default_primary_colour')) }};
            --secondary-colour : {{ session('site_secondary_colour', config('app.default_secondary_colour')) }};
        }
        .primary-colour { color: var(--primary-colour)  !important ;}
        .secondary-colour { color: var(--secondary-colour)  !important ;}
        .primary-background-colour { background-color: var(--primary-colour) !important ;}
        .secondary-background-colour { background-color: var(--secondary-colour) !important ;}
    </style>
</head>
<body>
    <div id="adminApp">    
        <main>
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ mix('js/admin.js') }}" defer></script>
    <p style="text-align:center; font-size: 10px; color: #666666;">{{ $version }}</p>    
</body>
</html>
