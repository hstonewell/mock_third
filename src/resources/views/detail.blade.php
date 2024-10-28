@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/detail.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__inner--image">
        <img src="{{ asset($item->image) }}">
    </div>
    <div class="main__inner--content">
        <div class="item-header">
            <h2>{{ $item->item_name }}</h2>
            <span>{{ $item->brand->brand_name }}</span>
            <h5>¥{{ number_format($item->price) }}</h5>
            <div class="item-header--buttons">
                @if(Auth::check())
                @if(count($item->favorites) == 0)
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
                        <i class="fa-solid fa-star fa-lg" style="color:#FFE500"></i>
                    </button>
                </form>
                @endif
                @endif
                <i class="fa-regular fa-comment fa-lg"></i>
            </div>
        </div>
        <form class="purchase-item">
            <button class="submit-button">購入する</button>
        </form>
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
                        <p>{{ $item->category->category_name }}</p>
                        <p>カテゴリ2</p>
                    </td>
                </tr>
                <tr>
                    <th>商品の状態</th>
                    <td class="item-condition">
                        <p>{{ $item->condition->condition }}</p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="item-comments">
            <div class="item-comment__unit">
                <div class="item-comment-user">
                    <img src="" class="thumbnail">
                    <span>名前</span>
                </div>
                <div class="item-comment-content">
                    <p>ないようないようないよう</p>
                </div>
            </div>
            <div class="item-comments__form">
                <form class="item-comments">
                    @csrf
                    <label>商品へのコメント</label>
                    <textarea class="item-comments__input" type="textarea" rows="5"></textarea>
                    <button class="submit-button">コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection