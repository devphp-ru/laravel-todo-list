<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

<div class="container">

    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('todo.index') }}">DEMO-SITE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('todo.index') }}">Список дел</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Выход') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login.form') }}">Вход</a>
                        </li>
                        @if (Route::has('register.form'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                        </li>
                        @endif
                    @endauth
                </ul>
                @auth
                <div class="navbar-text">
                    Пользователь: {{ Auth::user()->name }}
                </div>
                @endauth
            </div>
        </div>
    </nav>
    @yield('content')
</div>

@auth
<script>
    const currentUserId = @json(Auth::user()->id);
</script>

<script src="{{ asset('/assets/js/jquery.min.js') }}"></script>

<link href="{{ asset('/assets/libs/selectize/selectize.default.css') }}" rel="stylesheet">
<script src="{{ asset('/assets/libs/selectize/selectize.js') }}"></script>

<link href="{{ asset('/assets/libs/notice/toast.min.css') }}" rel="stylesheet">
<script src="{{ asset('/assets/libs/notice/toast.min.js') }}"></script>

<script src="{{ asset('/assets/js/script.js') }}"></script>
@endauth
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>