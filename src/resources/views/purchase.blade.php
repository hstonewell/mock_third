@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/purchase.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__inner--left">
        <div class="item-summary__column">
            <div class="item-summary__column--img">
                <img src="" alt="">
            </div>
            <div class="item-summary__column--caption">
                <h2>商品名</h2>
                <h5>¥100,000</h5>
            </div>
        </div>
        <div class="item-summary__column">
            <h3>支払い方法</h3>
            <a href="" class="item-summary__column--modify">変更する</a>
        </div>
        <div class="item-summary__column">
            <h3>配送先</h3>
            <a href="" class="item-summary__column--modify">変更する</a>
        </div>
    </div>
    <div class="main__inner-right">
        <form class="item-purchase--form">
            @csrf
            <div class="item-purchase__box">
                <div class="item-purchase__payment-unit">
                    <label>商品代金</label>
                    <input value="金額" readonly>
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