<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECHフリマ</title>
    <script src="https://kit.fontawesome.com/88521f16f4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            @if(Request::is('register') || Request::is('login'))
            <div class="header__inner--logo">
                <a href="/"><img src="{{ asset('img/logo.svg') }}" alt="COACHTECHフリマ"></a>
            </div>
            @elseif (Request::is('/') || Request::is('item/*') || Request::is('purchase/*') || Request::is('mypage') || Request::is('mypage/profile'))
            <div class="header__inner--logo">
                <a href="/"><img src="{{ asset('img/logo.svg') }}" alt="COACHTECHフリマ"></a>
            </div>
            <div class="header__inner--search">
                <form class="search-form" action="" method="GET">
                    @csrf
                    <input class="search-input" type="search" name="keyword" placeholder="何をお探しですか？" value="{{ old('keyword', request('keyword')) }}">
                </form>
            </div>
            <div class="header__inner--menu">
                @if (Auth::check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sell-link">ログアウト</button>
                </form>
                <a href="/mypage" class="sell-link">マイページ</a>
                @else
                <a href="/login" class="sell-link">ログイン</a>
                <a href="/register" class="sell-link">会員登録</a>
                @endif
                <a href="" class="sell-button">出品</a>
            </div>
            @elseif (Request::is('sell') || Request::is('purchase/address/*'))
            @endif
        </div>
    </header>
    <main class="main">
        @yield('main')
    </main>
</body>

</html>