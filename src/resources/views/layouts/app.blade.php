<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECHフリマ</title>
    <script src="https://kit.fontawesome.com/88521f16f4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__inner--logo">
                <img src="{{ asset('img/logo.svg') }}" alt="">
            </div>
        </div>
        @yield('header')
    </header>
    <main class="main">
        @yield('main')
    </main>
</body>

</html>