<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script type="text/javascript" href="js/app.js"></script>
    <title>Laravel</title>

</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="top-right links">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth

        @endif
        <a href="{{ url('/teste') }}">Teste</a>
    </div>
</div>
</body>
</html>
