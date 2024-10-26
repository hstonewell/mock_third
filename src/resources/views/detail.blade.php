@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/detail.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__inner--image">
        <img src="" class="item-image">
    </div>
    <div class="main__inner--content">
        <div class="item-header">
            <h2>商品名</h2>
            <span>ブランド名</span>
            <h5>¥100,000</h5>
            <div class="item-header--buttons">
                <i class="fa-regular fa-star fa-lg"></i>
                <i class="fa-regular fa-comment fa-lg"></i>
            </div>
        </div>
        <form class="purchase-item">
            <button class="submit-button">購入する</button>
        </form>
        <div class="item-description">
            <h3>商品説明</h3>
            <p>説明説明説明説明</p>
            <p>説明説明</p>
        </div>
        <div class="item-information">
            <h3>商品の情報</h3>
            <table class="item-information--table">
                <tr>
                    <th>カテゴリー</th>
                    <td class="item-category">
                        <p>カテゴリ1</p>
                    </td>
                    <td class="item-category">
                        <p>カテゴリ2</p>
                    </td>
                </tr>
                <tr>
                    <th>商品の状態</th>
                    <td class="item-condition">状態</td>
                </tr>
            </table>
        </div>
        <div class="item-comments__list">
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