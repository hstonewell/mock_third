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
    @livewireStyles
</head>

<body>
    <header class="header">
        <div class="header__inner">
            @if(Request::is('register', 'login') )
            <div class="header__inner--logo">
                <a href="{{ route('index') }}"><img src="{{ asset('img/logo.svg') }}" alt="COACHTECHフリマ"></a>
            </div>
            @elseif (Request::is('/', 'item/*', 'purchase/*', 'mypage', 'mypage/profile', 'search', 'thanks') && !Request::is('purchase/address/*') && !session('is_newly_registered'))
            <div class="header__inner--logo">
                <a href="{{ route('index') }}"><img src=" {{ asset('img/logo.svg') }}" alt="COACHTECHフリマ"></a>
            </div>
            <div class="header__inner--search">
                <form class="search-form" action="{{ route('search') }}" method="get">
                    @csrf
                    <input class="search-input" type="search" name="keyword" placeholder="何をお探しですか？" value="{{ old('keyword', request('keyword')) }}">
                </form>
            </div>
            <div class="header__inner--menu">
                @if (Auth::check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="header__link">ログアウト</button>
                </form>
                @hasanyrole('admin')
                <a href="{{ route('admin.index') }}" class="header__link">管理者メニュー</a>
                @else
                <a href="/mypage" class="header__link">マイページ</a>
                @endhasanyrole
                @else
                <a href="/login" class="header__link">ログイン</a>
                <a href="/register" class="header__link">会員登録</a>
                @endif
                <a href="/sell" class="sell-button">出品</a>
            </div>
            <livewire:humburgerMenu />
            @endif
            @yield('header')
        </div>
    </header>
    <main class="main">
        @yield('main')
    </main>
    @yield('script')
</body>

</html>