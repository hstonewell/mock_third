@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/mypage.css')}}">
<link rel="stylesheet" href="{{ asset('/css/items.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__profile">
        <div class="main__profile--img">
            @if($userProfile)
            <img src="{{ $userProfile->getProfileImageUrl() }}" class="profile-thumbnail">
            @else
            <img src="{{ asset('img/default-user-icon.svg') }}" class="profile-thumbnail">
            @endif
        </div>
        <div class="main__profile--name">
            <h1>{{ $userProfile->name ?? 'ユーザー名未設定' }}</h1>
        </div>
        <div class="main__profile--edit">
            <a href="{{ route('profile.show') }}" class="edit-button">プロフィールを編集</a>
        </div>
    </div>
    <div class="main__tab-wrapper">
        <input id="tab-selling" type="radio" name="tab-button" checked>
        <input id="tab-purchased" type="radio" name="tab-button">
        <div class="main__tab-labels">
            <div class="main__tab-labels__inner">
                <label class="main__tab-label tab-label--selling" for="tab-selling">出品した商品</label>
                <label class="main__tab-label tab-label--purchased" for="tab-purchased">購入した商品</label>
            </div>
        </div>
        <div class="main__tab-content">
            <div id="tab-panel--selling" class="tab-content__panel">
                <div class="tab-panel__items">
                    @foreach ($sellingItems as $sellingItem)
                    <div class="tab-panel__item-thumbnail">
                        <a href="{{ route('item.detail', ['item_id'=>$sellingItem->id]) }}">
                            <img src="{{ asset($sellingItem->image) }}" alt="{{ $sellingItem->item_name }}">
                            @if (optional($sellingItem->item)->sold_out)
                            <p class="soldout-tag">売り切れ</p>
                            @endif
                            <p class="price-tag">¥{{ number_format($sellingItem->price) }}</p>
                        </a>
                    </div>
                    @endforeach
                    <div class="tab-panel__spacer"></div>
                    <div class="tab-panel__spacer"></div>
                    <div class="tab-panel__spacer"></div>
                </div>
            </div>
            <div id="tab-panel--purchased" class="tab-content__panel">
                <div class="tab-panel__items">
                    @foreach ($purchasedItems as $purchasedItem)
                    <div class="tab-panel__item-thumbnail">
                        <a href="{{ route('item.detail', ['item_id'=>$purchasedItem->item->id]) }}">
                            <img src="{{ asset($purchasedItem->item->image) }}" alt="{{ $purchasedItem->item->item_name }}">
                            @if ($purchasedItem->item->sold_out == true)
                            <p class="soldout-tag">売り切れ</p>
                            @endif
                            <p class="price-tag">¥{{ number_format($purchasedItem->item->price) }}</p>
                        </a>
                    </div>
                    @endforeach
                    <div class="tab-panel__spacer"></div>
                    <div class="tab-panel__spacer"></div>
                    <div class="tab-panel__spacer"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection