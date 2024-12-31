<div class="menu__button">
    @if(!$showMenu)
    <div class="open-button">
        <button wire:click="openMenu" type="button" class="menu-button" aria-label="Open menu">
            <i class="fa-solid fa-bars fa-2xl" style="color: #ffffff;"></i>
        </button>
    </div>
    @endif
    @if($showMenu)
    <div class="close-button">
        <button wire:click="closeMenu" type="button" class="menu-button">
            <i class="fa-solid fa-xmark fa-2xl" style="color: #5f5f5f;"></i>
        </button>
    </div>
    <div class="menu__content">
        <div class="menu__inner">
            @if (Auth::check())
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu__link">ログアウト</button>
            </form>
            @hasanyrole('admin')
            <a href="{{ route('admin.index') }}" class="menu__link">管理者メニュー</a>
            @endhasanyrole
            <a href="/mypage" class="menu__link">マイページ</a>
            @else
            <a href="/login" class="menu__link">ログイン</a>
            <a href="/register" class="menu__link">会員登録</a>
            @endif
            <a href="/sell" class="menu__link">出品</a>
        </div>
    </div>
    @endif
</div>