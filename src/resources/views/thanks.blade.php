@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/thanks.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__header">
        <h1>購入完了</h1>
        <p>ご購入ありがとうございます！</p>
    </div>
    <div class="item">
        <div class="item__image">
            <img src="{{ asset($item->image) }}">
        </div>
        <div class="item__box">
            <div class="detail">
                <h2>{{ $item->item_name }}</h2>
                <ul>
                    <li>ブランド名: {{ $item->brand_name }}</li>
                    <li>金額: ¥{{ number_format($item->price) }}</li>
                    <li>購入方法: クレジットカード</li>
                    <li>{{ $item->description }}</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="item--back-button">
        <a href="/" class="edit-button">TOPページへ戻る</a>
    </div>
</div>
@endsection