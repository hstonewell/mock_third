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
        <div class="item-summary--option-unit">
            <h3>支払い方法</h3>
            <a href="">変更する</a>
        </div>
        <div class="item-summary--option-unit">
            <h3>配送先</h3>
            <a href="">変更する</a>
        </div>
    </div>
    <div class="main__inner-right">
        <form class="item-purchase--form">
            @csrf
            <div class="item-purchase__box">
                <div class="item-purchase__payment-unit">
                    <label>商品代金</label>
                    <input value="¥{{ number_format($item->price) }}" readonly>
                </div>
                <div class="item-purchase__payment-unit">
                    <label>支払い金額</label>
                    <input value="金額" readonly>
                </div>
                <div class="item-purchase__payment-unit">
                    <label>支払い方法</label>
                    <input value="支払い方法" readonly>
                </div>
            </div>
            <div class="item-purchase--button">
                <button class="submit-button">購入する</button>
        </form>
    </div>
    </form>
</div>
</div>
@endsection