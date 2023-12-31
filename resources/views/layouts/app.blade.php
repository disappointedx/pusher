<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <?php header('Access-Control-Allow-Origin: *'); ?>
</head>
<body>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
            <a class="navbar-brand nav-name" href="{{ url('/') }}">
    {{ config('app.name', 'Laravel') }}
</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
    @guest
    @else    
<!-- Left Side Of Navbar -->
    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown mx-2">
            <div class="navbar-brand mx-auto">
                <div class="text-center"><a href="/room" class = "btn fs-5">Игровые Комнаты</a></div>
            </div>
        </li>
        <li class="nav-item dropdown mx-2">
            <div class="navbar-brand mx-auto">
                <div class="text-center"><a href="/test" class = "btn fs-5">Создать тест</a></div>
            </div>
        </li>
    </ul>
    @endguest
    <!-- Right Side Of Navbar -->
    <ul class="navbar-nav ms-auto">
        <!-- Authentication Links -->
        @guest
        @if (Route::has('login'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}"> Авторизация </a>
        </li>
        @endif

        @if (Route::has('register'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}"> Регистрация </a>
        </li>
        @endif
        @else
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle fs-4" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/profile">
                    Мой аккаунт
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Выйти
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
        @endguest
    </ul>
</div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
@yield('style')
<style>
body {   
/* Местоположение фоновой картинки */   background-image: url('https://www.toptal.com/designers/subtlepatterns/uploads/email-pattern.png');      
/* Фоновое изображение выровнено по центру в горизонтальной и вертикальной плоскостях */   background-position: center center;      
/* Фон не повторяется */   background-repeat: no-repeat;      
/* Фон зафиксирован в области просмотра и потому не двигается, когда высота контента превышает высоту изображения */   background-attachment: fixed;      
/* Это свойство заставляет фон менять масштаб при изменении размеров содержащего его контейнера*/   background-size: cover;      
/* Цвет фона, который будет отображаться при загрузке фоновой картинки*/   background-color: white; }
.nav-name{
    font-size: 25px;
    font-weight: 500;
}
</style>
{{--    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>--}}
</body>
</html>
