@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/index.css')}}">
<link rel="stylesheet" href="{{ asset('/css/items.css')}}">
@endsection

@section('main')
<div class="main">
    @if ($errors->any())
    <div class="error">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </div>
    @endif
    @if (session()->has('error'))
    <div class="error">
        <li>{{ session('error') }}</li>
    </div>
    @endif
    @if (session()->has('success'))
    <div class="success">
        <li>{{ session('success') }}</li>
    </div>
    @endif
    <div class="main__tab-wrapper">
        <input id="tab-search" type="radio" name="tab-button" {{ session('fs_msg') ? 'checked' : '' }}>
        <input id="tab-recommend" type="radio" name="tab-button" {{ session('fs_msg') ? '' : 'checked' }}>
        <input id="tab-mylist" type="radio" name="tab-button">
        <div class="main__tab-labels">
            <div class="main__tab-labels__inner">
                @if(session('fs_msg'))
                <label class="main__tab-label tab-label--search" for="tab-search">{{ session('fs_msg') }}</label>
                @endif
                <label class="main__tab-label tab-label--recommend" for="tab-recommend">おすすめ</label>
                <label class="main__tab-label tab-label--mylist" for="tab-mylist">マイリスト</label>
            </div>
        </div>
        <div class="main__tab-content">
            @if(session('fs_msg'))
            <div id="tab-panel--search" class="tab-content__panel">
                <div class="tab-panel__items">
                    @forelse ($searchResults ?? [] as $searchResult)
                    <div class="tab-panel__item-thumbnail">
                        <a href="{{ route('item.detail', ['item_id' => $searchResult->id]) }}">
                            <img src="{{ asset($searchResult->image) }}" alt="{{ $searchResult->item_name }}">
                        </a>
                    </div>
                    @empty
                    <div class="tab-panel__not-found">
                        <p class="tab-panel__not-found-text">該当する商品が見つかりませんでした。
                        </p>
                    </div>
                    @endforelse
                    <div class="tab-panel__spacer"></div>
                    <div class="tab-panel__spacer"></div>
                    <div class="tab-panel__spacer"></div>
                </div>
            </div>
            @endif
            <div id="tab-panel--recommend" class="tab-content__panel">
                <div class="tab-panel__items">
                    @foreach ($recommendItems as $recommendItem)
                    <div class="tab-panel__item-thumbnail">
                        <a href="{{ route('item.detail', ['item_id'=>$recommendItem->id]) }}">
                            <img src="{{ asset($recommendItem->image) }}" alt="{{ $recommendItem->item_name }}">
                        </a>
                    </div>
                    @endforeach
                    <div class="tab-panel__spacer"></div>
                    <div class="tab-panel__spacer"></div>
                    <div class="tab-panel__spacer"></div>
                </div>
            </div>
            <div id="tab-panel--mylist" class="tab-content__panel">
                <div class="tab-panel__items">
                    @if (Auth::check())
                    @foreach ($favoriteItems as $favoriteItem)
                    <div class="tab-panel__item-thumbnail">
                        <a href="{{ route('item.detail', ['item_id'=>$favoriteItem->item->id]) }}">
                            <img src="{{ asset($favoriteItem->item->image) }}" alt="{{ $favoriteItem->item->item_name }}">
                            @if ($favoriteItem->item->sold_out == true)
                            <p class="soldout-tag">売り切れ</p>
                            @endif
                        </a>
                    </div>
                    @endforeach
                    @else
                    <div class="tab-panel__not-found">
                        <p class="tab-panel__not-found-text">マイリストに商品を登録するには
                            <a href="/login" class="tab-panel__not-found-link">ログイン</a>してください
                        </p>
                    </div>
                    @endif
                    <div class="tab-panel__spacer"></div>
                    <div class="tab-panel__spacer"></div>
                    <div class="tab-panel__spacer"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection