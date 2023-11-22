<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <main>
            <header>
                <h1><a href="{{ url('/posts') }}">NEWS4U!</a></h1>
                <form action="{{ route('search.results') }}" method="GET">
                    <input type="text" name="query" placeholder="Search...">
                    <button type="submit">Search</button>
                </form>                
                @if(Auth::check() && Auth::user()->isAdmin()->exists() && !Route::is('admin.dashboard'))
                    <a class="button" href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
                @if (Auth::check())
                    <a class="button" href="{{ url('/logout') }}"> Logout </a><a class="button" href="{{ route('profile.show', ['username' => Auth::user()->username]) }}" class="btn">{{Auth::user()->username}}</a>
                @endif
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
</html>