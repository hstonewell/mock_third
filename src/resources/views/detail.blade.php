@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/detail.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__inner--image">
        <img src="{{ asset($item->image) }}">
        @if ($item->sold_out == true)
        <p class="soldout-tag">売り切れ</p>
        @endif
    </div>
    <div class="main__inner--content">
        <div class="item-header">
            <h2>{{ $item->item_name }}</h2>
            <span>{{ $item->brand_name }}</span>
            <h5>¥{{ number_format($item->price) }}</h5>
            <div class="item-header--reactions">
                <div class="item-header--reactions-icon">
                    @if(Auth::check())
                    @if(!$favorites)
                    <form method="POST" action="{{ route('favorite', ['item_id' => $item->id]) }}">
                        @csrf
                        <button type="submit">
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
                    <i class="fa-regular fa-star fa-xl"></i>
                    @endif
                    <p>{{ $item->favorites_count }}</p>
                </div>
                <div class="item-header--reactions-icon">
                    <a href="#comments"><i class="fa-regular fa-comment fa-2xl"></i></a>
                    <p>{{ $item->comments->count() }}</p>
                </div>
            </div>
        </div>
        <div class="purchase-item">
            @if ($item->sold_out == false && $item->user_id !== Auth::id() )
            <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="submit-button">購入する</a>
            @else
            <a disabled class="submit-button">購入する</a>
            @endif
        </div>
        <div class="item-description">
            <h3>商品説明</h3>
            <p>{{ $item->description }}</p>
        </div>
        <div class="item-information">
            <h3>商品の情報</h3>
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
                    <form class="item-comment-content--delete" method="post" action="{{ route('comment.delete', ['comment_id' => $comment->id]) }}">
                        @csrf
                        <button type="submit" @if(Auth::id()!=$comment->user_id) hidden @endif><i class="fa-solid fa-trash-can" style="color:#d9d9d9"></i>削除</button>
                    </form>
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