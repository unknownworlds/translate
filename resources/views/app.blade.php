<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Translations :: Unknown Worlds</title>

    <link href="{{ asset('/css/bootstrap_'.(isset(Auth::user()->name) ? Auth::user()->theme : 'light').'.css') }}"
          rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
</head>

<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Translations</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/translations') }}">Translate</a></li>
                <li><a href="{{ url('/pages/instructions') }}">Instructions</a></li>
                <li><a href="{{ url('/pages/guidelines') }}">Guidelines</a></li>
                <li><a href="{{ url('/pages/frequently-asked-questions') }}">FAQ</a></li>
                <li><a href="{{ url('/rss') }}">RSS</a></li>
                @if(env('TRANSLATION_FORUMS_URL'))
                    <li><a href="{{ env('TRANSLATION_FORUMS_URL') }}">Translations forums</a></li>
                @endif
                @if ( !Auth::guest() && Auth::user()->hasRole('Root'))
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">Manage <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/users') }}">Users</a></li>
                            <li><a href="{{ url('/roles') }}">Roles</a></li>
                            <li><a href="{{ url('/languages') }}">Languages</a></li>
                            <li><a href="{{ url('/projects') }}">Projects</a></li>
                            <li><a href="{{ url('/pages') }}">Pages</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">Admin tool <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/admin-tool/language-status') }}">Language status</a></li>
                            <li><a href="{{ url('/admin-tool/potential-admins') }}">Potential admins</a></li>
                            <li><a href="{{ url('/admin-tool/audit') }}">Audit</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">Stats <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/stats/translations-per-day') }}">Translations per day</a></li>
                            <li><a href="{{ url('/stats/users-per-day') }}">Users per day</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">Tools <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/tools/translation-quality') }}">Quality control</a></li>
                            <li><a href="{{ url('/tools/file-import') }}">JSON file import</a></li>
                            <li><a href="{{ url('/tools/translations-transfer') }}">Translations transfer</a></li>
                        </ul>
                    </li>
                @endif
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    @if(env('BUS_LOGIN_ENABLED'))
                        <li><a href="{{ env('BUS_HOST') }}/register" target="_blank">Register</a></li>
                    @else
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @endif
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">Theme <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/theme/light') }}">Light</a></li>
                            <li><a href="{{ url('/theme/dark') }}">Dark</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false" v-pre>{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('/logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//cdn.jsdelivr.net/lodash/4.13.1/lodash.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.0.3/vue.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.0.2/Chart.js"></script>
{{--<script src="//vuejs.org/js/vue.js"></script>--}}
@yield('scripts')

</body>
</html>
