@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/purchase.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__inner--left">
        <div class="item-summary">
            <div class="item-summary--img">
                <img src="{{ asset($item->image) }}" alt="{{ $item->item_name }}">
            </div>
            <div class="item-summary--caption">
                <h2>{{ $item->item_name }}</h2>
                <h5>¥{{ number_format($item->price) }}</h5>
            </div>
        </div>
        <div class="item-summary--option">
            <div class="item-summary--option--header">
                <h3>支払い方法</h3>
                <a href="">変更する</a>
            </div>
        </div>
        <div class="item-summary--option">
            <div class="item-summary--option--header">
                <h3>配送先</h3>
                <a href="{{ route('address.show', ['item_id' => $item->id]) }}">変更する</a>
            </div>
            <div class="item-summary--option--detail">
                @if($userProfile)
                <p>〒{{ $userProfile->postcode }}</p>
                <p>{{ $userProfile->address }} {{ $userProfile->building }}</p>
                @else
                <p>住所を設定してください。</p>
                @endif
            </div>
        </div>
    </div>
    <div class="main__inner-right">
        <form method="post" action="{{ route('purchase.create', ['item_id' => $item->id]) }}" class="item-purchase--form">
            @csrf
            <div class="item-purchase__box">
                <div class="item-purchase__payment-unit">
                    <label>商品代金</label>
                    <input value="¥{{ number_format($item->price) }}" readonly>
                </div>
                <div class="item-purchase__payment-unit">
                    <label>支払い金額</label>
                    <input value="¥{{ number_format($item->price) }}" readonly>
                </div>
                <div class="item-purchase__payment-unit">
                    <label>支払い方法</label>
                    <input value="支払い方法" readonly>
                </div>
            </div>
            <div class="item-purchase--button">
                @if($item->sold_out == false)
                <button class="submit-button" {{ !$hasUserAddress ? 'disabled' : '' }}>購入する</button>
                @else
                <button class="submit-button" disabled>売り切れ</button>
                @endif
        </form>
    </div>
    </form>
</div>
</div>
@endsection