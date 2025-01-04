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
                <h1>{{ $item->item_name }}</h1>
                <h3>¥{{ number_format($item->price) }}</h3>
            </div>
        </div>
        <div class="item-summary--option">
            <div class="item-summary--option--header">
                <h2>支払い方法</h2>
            </div>
            <div class="item-summary--option--detail">
                <form id="payment-form" method="post" action="{{ route('payment.create', ['item_id' => $item->id]) }}">
                    @csrf
                    <label>
                        <input type="radio" name="payment" value="クレジットカード" checked onchange="updatePaymentMethod(this)"> クレジットカード
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="payment" value="銀行振込" onchange="updatePaymentMethod(this)"> 銀行振込
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="payment" value="コンビニ支払" onchange="updatePaymentMethod(this)"> コンビニ支払
                    </label>

                    <input type="hidden" name="name" value="{{ $item->item_name }}">
                    <input type="hidden" name="price" value="{{ $item->price }}">
                    <input type="hidden" name="payment_method" id="selected-payment-input" value="クレジットカード">
                </form>
            </div>
        </div>
        <div class="item-summary--option">
            <div class="item-summary--option--header">
                <h2>配送先</h2>
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
        <div class="item-purchase__box">
            <div class="item-purchase__payment-unit">
                <p>商品代金</p>
                <p>¥{{ number_format($item->price) }}</p>
            </div>
            <div class="item-purchase__payment-unit">
                <p>支払い金額</p>
                <p>¥{{ number_format($item->price) }}</p>
            </div>
            <div class="item-purchase__payment-unit">
                <p>支払い方法</p>
                <p id="selected-payment-method">選択されていません</p>
            </div>
            <div class="item-purchase--button">
                @if($item->sold_out == false)
                <button id="submit" class="submit-button" onclick="submitForm()" aria-controls="payment-form" {{ !$hasUserAddress ? 'disabled' : '' }}>購入する</button>
                @else
                <button id="submit" class="submit-button" disabled>売り切れ</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function updatePaymentMethod(radio) {
        document.getElementById('selected-payment-method').textContent = radio.value;
        document.getElementById('selected-payment-input').value = radio.value;
    }

    function submitForm() {
        const paymentInput = document.getElementById('selected-payment-input').value;
        if (!paymentInput) {
            alert('支払い方法を選択してください。');
            return false;
        }
        document.getElementById('payment-form').submit();
    }

    document.addEventListener('DOMContentLoaded', () => {
        updatePaymentMethod(document.querySelector('input[name="payment"]:checked'));
    });
</script>

@section('script')
@endsection