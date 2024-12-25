@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/thanks.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__header">
        <h2>購入完了</h2>
    </div>
    <div class="item">
        <div class="item__image">
            <img src="{{ asset($item->image) }}">
        </div>
        <div class="item__box">
            <div class="detail">
                <h3>{{ $item->item_name }}</h3>
                <p>¥{{ number_format($item->price) }}</p>
            </div>
            <div class="instruction">
                    <div class="instruction__header">
                        <h3>振込手順</h3>
                    </div>
                    <div class="instruction__content">
                        <p>以下の銀行情報を使用してお振り込みください。</p>
                        <ul>
                            <li>金融機関コード: {{ $zenginDetails['bank_code'] }}</li>
                            <li>振込先銀行: {{ $zenginDetails['bank_name'] }}</li>
                            <li>支店番号: {{ $zenginDetails['branch_code'] }}</li>
                            <li>支店名: {{ $zenginDetails['branch_name'] }}</li>
                            <li>講座種別: {{ $zenginDetails['account_type'] === 'futsu' ? '普通' : '当座' }}</li>
                            <li>口座番号: {{ $zenginDetails['account_number'] }}</li>
                            <li>口座名義: {{ $zenginDetails['account_holder_name'] }}</li>
                        </ul>
                    </div>
            </div>
        </div>
    </div>
    <div class="item--back-button">
        <a href="/" class="edit-button">TOPページへ戻る</a>
    </div>
</div>
@endsection