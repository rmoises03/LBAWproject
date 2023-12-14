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
                <h1><a href="{{ url('/posts') }}">NEWS4U</a></h1>
                <form action="{{ route('search.results') }}" method="GET" id="search_bar">
                    <input type="text" name="query" placeholder="Search...">
                </form>
                <button id="menuToggle" class="button">☰</button> <!-- Menu Toggle Button -->
                <div id="sidebarMenu" class="sidebar">
                    <span id="closeSidebar" class="close-sidebar">&times;</span>
                    <div id="buttons">
                        @if(Auth::check() && Auth::user()->isAdmin()->exists() && !Route::is('admin.dashboard'))
                            <a class="button" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        @endif
                        @if (Auth::check())
                            <a class="button" href="{{ url('/logout') }}">Logout</a>
                            <a class="button" href="{{ route('profile.show', ['username' => Auth::user()->username]) }}">{{Auth::user()->username}}</a>
                        @else
                            <a class="button" href="{{ url('/login') }}">Login</a>
                        @endif
                        <a class="button" href="{{ route('about') }}">About Us</a>
                    </div>
                </div>
            </header>            
            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
</html>