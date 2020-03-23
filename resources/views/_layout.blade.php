@php
$app_name = config('app.name', 'Laravel');
$app_url = url('/');
@endphp

<!doctype html>
<html lang="{{__('t.html_lang_attribute')}}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <title>@yield('meta_title', __('t.default_meta_title',['app_name' => $app_name, 'app_url' => $app_url]))</title>
    <meta name="keywords" content="@yield('meta_keywords',__('t.default_meta_keywords',['app_name' => $app_name, 'app_url' => $app_url]))"/>
    <meta name="description" content="@yield('meta_description',__('t.default_meta_description',['app_name' => $app_name, 'app_url' => $app_url]))"/>


    <meta name="application-name" content="{{$app_name}}">
    <meta name="theme-color" content="#ffffff">

    <!-- favicons -->
    <link rel="shortcut icon" href="/favicons/favicon.ico?v=Gvb0oRr33W">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png?v=Gvb0oRr33W">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png?v=Gvb0oRr33W">
    <link rel="icon" type="image/png" sizes="194x194" href="/favicons/favicon-194x194.png?v=Gvb0oRr33W">

    <!-- icons for ios -->
    <meta name="apple-mobile-web-app-title" content="SendPass">
    <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-touch-icon-57x57.png?v=Gvb0oRr33W">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-touch-icon-60x60.png?v=Gvb0oRr33W">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-touch-icon-72x72.png?v=Gvb0oRr33W">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicons/apple-touch-icon-76x76.png?v=Gvb0oRr33W">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicons/apple-touch-icon-114x114.png?v=Gvb0oRr33W">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicons/apple-touch-icon-120x120.png?v=Gvb0oRr33W">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicons/apple-touch-icon-144x144.png?v=Gvb0oRr33W">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicons/apple-touch-icon-152x152.png?v=Gvb0oRr33W">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon-180x180.png?v=Gvb0oRr33W">

    <!-- icons for android -->
    <link rel="icon" type="image/png" sizes="192x192" href="/favicons/android-chrome-192x192.png?v=Gvb0oRr33W">
    <link rel="manifest" href="/favicons/site.webmanifest?v=Gvb0oRr33W">

    <!-- icons for microsoft -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/favicons/mstile-144x144.png?v=Gvb0oRr33W">
    <meta name="msapplication-config" content="/favicons/browserconfig.xml?v=Gvb0oRr33W">

    <!-- icons for safari -->
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg?v=Gvb0oRr33W" color="#2b5797">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Alternate Languages Links -->
    @foreach ($supported_locale2kit AS $supported_locale => $supported_locale_kit)
        <link rel="alternate" hreflang="{{$supported_locale}}" href="{{$supported_locale_kit['url']}}">
    @endforeach



    <!-- Scripts -->
    <script>
        window.app_locale = "{{$app_locale}}";
    </script>
    {{--    <script src="{{ mix('/llmix/manifest.js') }}" async></script>--}}
    {{--    <script src="{{ mix('/llmix/vendor.js') }}" async></script>--}}
    <script src="{{ mix('/llmix/app.js') }}" async></script>


    <!-- Styles -->
{{--    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet';" href="{{ mix('/llmix/bootstrap.css') }}">--}}
{{--    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet';" href="{{ mix('/llmix/fontawesome.css') }}">--}}
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet';" href="{{ mix('/llmix/app.css') }}">
    <noscript>
{{--        <link rel="stylesheet" href="{{ mix('/llmix/bootstrap.css') }}">--}}
{{--        <link rel="stylesheet" href="{{ mix('/llmix/fontawesome.css') }}">--}}
        <link rel="stylesheet" href="{{ mix('/llmix/app.css') }}">
    </noscript>


    <style type="text/css">
        body>div.loading {
            display: block;

            position:fixed;left:0;top:0;right:0;bottom:0;margin:auto;

            width: 2rem;
            height: 2rem;
        }
        body>div.loading>div.pre-spinner {
            display: inline-block;

            width: 2rem;
            height: 2rem;
            vertical-align: text-bottom;
            border: 0.25em solid #6c757d;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: loading-pre-spinner 0.75s linear infinite;
            animation: loading-pre-spinner 0.75s linear infinite;
        }
        @-webkit-keyframes loading-pre-spinner { to { -webkit-transform: rotate(-360deg); transform: rotate(-360deg); } }
        @keyframes loading-pre-spinner { to { -webkit-transform: rotate(-360deg); transform: rotate(-360deg); } }
        body>div.loading>div.pre-spinner>span { display: none; }

        body>div.loading>div.pst-spinner { display: none; }

        body>header { display: none; }

        body>main { display: none; }

        body>footer { display: none; }

        body>div.css-loaded { display: none; }
        body>div.js-loaded { display: none; }
    </style>
</head>
<body class="loading h-100 d-flex flex-column bg-light">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NJNF7MH" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="loading">
        <div class="pre-spinner d-none" role="status"><span>Loading...</span></div>
        <div class="pst-spinner d-block spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>
    </div>

    <header class="d-block bg-white">
        <nav class="navbar sticky-top navbar-expand-sm navbar-light shadow-sm">

            <div class="container" id="navbar-container">
                <a class="navbar-brand sendpass-brand" href="{{ $app_url }}">
                    <img src="/logo-64.svg" width="32px" height="32px" onerror="this.onerror=null;this.src='/logo-64.png';" alt="{{ $app_name }}">
                    <span>{{ $app_name }}</span>
                </a>



                <button class="navbar-toggler select-locale-small" type="button" data-toggle="collapse" data-target="#select-locale-small-content" >
                    @svg('ctrf/'.$app_locale_kit['flag_svg_file_name'])
                </button>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-elements-content">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="navbar-collapse collapse" id="select-locale-small-content" data-parent="#navbar-container">
                    <ul class="navbar-nav select-locale-small">
                        @foreach ($supported_locale2kit AS $supported_locale => $supported_locale_kit)
                            <li class="nav-item {{ ($supported_locale === $app_locale) ? 'active' : '' }}">
                                <a class="nav-link" href="{{$supported_locale_kit['url']}}">
                                    @svg('ctrf/'.$supported_locale_kit['flag_svg_file_name'])
                                    <span>{{__('t.lang_title',[],$supported_locale)}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="navbar-collapse collapse" id="navbar-elements-content"  data-parent="#navbar-container">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item {{ Request::is('/') ? 'active' : '' }}"><a class="nav-link text-capitalize" href="{{$app_url}}">{{__('t.protect')}}</a></li>
                        <li class="nav-item {{ Request::routeIs('faq') ? 'active' : '' }}"><a class="nav-link text-capitalize" href="{{route('faq')}}">{{__('t.faq')}}</a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
{{--                        <!-- Authentication Links -->--}}
{{--                        @guest--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>--}}
{{--                            </li>--}}
{{--                            @if (Route::has('register'))--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>--}}
{{--                                </li>--}}
{{--                            @endif--}}
{{--                        @else--}}
{{--                            <li class="nav-item dropdown">--}}
{{--                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>--}}
{{--                                    {{ Auth::user()->name }} <span class="caret"></span>--}}
{{--                                </a>--}}

{{--                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">--}}
{{--                                    <a class="dropdown-item" href="{{ route('logout') }}"--}}
{{--                                       onclick="event.preventDefault();--}}
{{--                                                     document.getElementById('logout-form').submit();">--}}
{{--                                        {{ __('Logout') }}--}}
{{--                                    </a>--}}

{{--                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
{{--                                        @csrf--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                        @endguest--}}
                    </ul>
                </div>

                <ul class="navbar-nav select-locale-big d-none d-sm-flex">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{$app_locale_kit['url']}}" id="select-locale-2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @svg('ctrf/'.$app_locale_kit['flag_svg_file_name'])
                            <span>{{__('t.lang_title')}}</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="select-locale-2">
                            @foreach ($supported_locale2kit AS $supported_locale => $supported_locale_kit)
                                <a class="dropdown-item {{ ($supported_locale === $app_locale) ? 'active' : '' }}" href="{{$supported_locale_kit['url']}}">
                                    @svg('ctrf/'.$supported_locale_kit['flag_svg_file_name'])
                                    <span>{{__('t.lang_title',[],$supported_locale)}}</span>
                                </a>
                            @endforeach
                        </div>
                    </li>
                </ul>

            </div>
        </nav>
    </header>


    <main role="main" class="bg-light py-3 flex-shrink-0">

        @yield('before-container')

        <div class="container">
            <div class="row">
                <div class="col col-md-1 col-xl-2"></div>
                <div class="col-12 col-md-10 col-xl-8">
                    @yield('content')
                </div>
                <div class="col col-md-1 col-xl-2"></div>
            </div>
        </div>



    </main>


    <footer class="d-block bg-darkblue text-white py-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-4">
{{--                    FOOTER--}}
                    <div class="mmnt-date-time">{{\Illuminate\Support\Carbon::now()->toIso8601String()}}</div>

                </div>
                <div class="col-12 col-sm-4">
                </div>
                <div class="col-12 col-sm-4 text-center text-sm-right">
                    <a class="navbar-brand sendpass-brand mr-0" href="{{ $app_url }}">
                        <img src="/logo-inverted-64.svg" width="32px" height="32px" onerror="this.onerror=null;this.src='/logo-inverted-64.png';" style="width:auto;height:auto;max-height:2.5rem;" class="d-inline-block" alt="{{ $app_name }}">
                        <span class="text-white">{{ $app_name }}</span>
                    </a>
                    <div>© 2020 @php echo ((intval(date('Y')) > 2020) ? ' - '.intval(date('Y')) : '') @endphp</div>
                </div>
            </div>
            <div class="row">
                <div class="col text-white-50 text-center">
                    <small>@lang('t.keep_it_secret_keep_it_safe')</small>
                </div>
            </div>
        </div>

    </footer>


{{--    <div class="css-loaded d-block">CSS-LOADED</div>--}}
{{--    <div class="js-loaded">JS-LOADED</div>--}}


</body>
</html>
