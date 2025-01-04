@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/detail.css')}}">
@endsection

@section('main')
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
<div class="main__inner">
    <div class="main__inner--image">
        <img src="{{ asset($item->image) }}">
        @if ($item->sold_out == true)
        <p class="soldout-tag">売り切れ</p>
        @endif
    </div>
    <div class="main__inner--content">
        <div class="item-header">
            <h1>{{ $item->item_name }}</h1>
            <span>{{ $item->brand_name }}</span>
            <h3>¥{{ number_format($item->price) }}</h3>
            <table class="item-header--reactions">
                <tr class="item-header--reactions-icon">
                    <td>
                        @if(Auth::check())
                        @if(!$favorites)
                        <form method="POST" action="{{ route('favorite', ['item_id' => $item->id]) }}">
                            @csrf
                            <button type="submit" @if($item->user_id == Auth::id()) disabled @endif>
                                <i class="fa-regular fa-star fa-xl"></i>
                            </button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('unfavorite', ['item_id' => $item->id]) }}">
                            @csrf
                            <button type="submit">
                                <i class="fa-solid fa-star fa-xl" style="color:#FFE500"></i>
                            </button>
                        </form>
                        @endif
                        @else
                        <i class="fa-regular fa-star fa-xl" style="transform: scale(2)"></i>
                        @endif
                    </td>
                    <td>
                        <a href="#comments"><i class="fa-regular fa-comment fa-xl"></i></a>
                    </td>
                </tr>
                <tr class="item-header--reactions-icon">
                    <td>
                        <p>{{ $item->favorites_count }}</p>
                    </td>
                    <td>
                        <p>{{ $item->comments->count() }}</p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="purchase-item">
            @if ($item->sold_out == false && $item->user_id !== Auth::id() )
            <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="submit-button">購入する</a>
            @else
            <a disabled class="submit-button">購入する</a>
            @endif
        </div>
        <div class="item-description">
            <h2>商品説明</h2>
            <p>{{ $item->description }}</p>
        </div>
        <div class="item-information">
            <h2>商品の情報</h2>
            <table class="item-information--table">
                <tr>
                    <th>カテゴリー</th>
                    <td class="item-category">
                        @if ($parentCategory)
                        <p>{{ $parentCategory }}</p>
                        @endif
                        @if ($childCategory)
                        <p>{{ $childCategory }}</p>
                        @else
                        <p>なし</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>商品の状態</th>
                    <td class="item-condition">
                        <p>{{ $condition ?: '-' }}</p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="item-comments" id="comments">
            @foreach ( $comments as $comment )
            <div class="item-comment__unit">
                <div class="item-comment-user" @if(Auth::id()===$comment->user_id) id="auth" @endif>
                    <img src="{{ $comment->user->userProfile ? $comment->user->userProfile->getProfileImageUrl() : asset('img/default-user-icon.svg') }}" class="user-icon">
                    <span>{{ $comment->user->userProfile->name ?? 'ユーザー名未設定' }}</span>
                </div>
                <div class="item-comment-content" @if(Auth::id()===$comment->user_id) id="auth" @endif>
                    <p>{{ $comment->comment }}</p>
                    @auth
                    @if(Auth::id()==$comment->user_id || Auth::user()->hasRole('admin'))
                    <form class="item-comment-content--delete" method="post" action="{{ route('comment.destroy', ['comment_id' => $comment->id]) }}">
                        @csrf
                        <button type="submit"><i class="fa-solid fa-trash-can" style="color:#d9d9d9"></i>削除</button>
                    </form>
                    @endif
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
        <div class="item-comments__form">
            <form method="post" action="{{ route('comment.create') }}" class="item-comments">
                @csrf
                <label>商品へのコメント</label>
                <input type="hidden" value="{{ $item->id }}" name="item_id">
                <textarea class="item-comments__input" type="textarea" rows="5" name="comment"></textarea>
                @if ($errors->has('comment'))
                @foreach($errors->get('comment') as $message)
                <p class="form--error-message">
                    {{ $message }}
                </p>
                @endforeach
                @endif
                <button type="submit" class="submit-button">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection