@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/thanks.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__header">
        <h1>購入完了</h1>
    </div>
    <div class="item">
        <div class="item__image">
            <img src="{{ asset($item->image) }}">
        </div>
        <div class="item__box">
            <div class="detail">
                <h2>{{ $item->item_name }}</h2>
                <p>¥{{ number_format($item->price) }}</p>
            </div>
            <div class="instruction">
                <div class="instruction__header">
                    <h2>コンビニ支払い手順</h2>
                </div>
                <div class="instruction__content">
                    <ol>
                        <li>支払いに利用するコンビニエンスストアチェーンの支払いコードと確認番号を確認します。</li>
                        <li>コンビニエンスストアで、支払い端末か、レジで支払いコードと確認番号を指定します。</li>
                        <li>支払いが完了したら、記録のために領収書を保管しておきます。</li>
                        <li>ご不明な点がございましたら、お問い合わせください。</li>
                    </ol>
                    <div class="store-tab__wrapper">
                        <input id="tab-familymart" type="radio" name="tab-button" checked>
                        <input id="tab-lawson" type="radio" name="tab-button">
                        <input id="tab-ministop" type="radio" name="tab-button">
                        <input id="tab-seicomart" type="radio" name="tab-button">
                        <div class="tab-labels">
                            <div class="tab-labels__inner">
                                <label class="store-label label-familymart" for="tab-familymart">ファミリーマート</label>
                                <label class="store-label label-lawson" for="tab-lawson">ローソン</label>
                                <label class="store-label label-ministop" for="tab-ministop">ミニストップ</label>
                                <label class="store-label label-seicomart" for="tab-seicomart">セイコーマート</label>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div id="familymart" class="tab-content__panel">
                                <ul>
                                    <li>支払いコード: {{ $konbiniDetails->stores->familymart->payment_code }}</li>
                                    <li>確認番号: {{ $konbiniDetails->stores->familymart->confirmation_number }}</li>
                                    <li>支払い期日: {{ \Carbon\Carbon::parse($konbiniDetails->expires_at)->format('Y-m-d H:i') }}</li>
                                </ul>
                            </div>
                            <div id="lawson" class="tab-content__panel">
                                <ul>
                                    <li>支払いコード: {{ $konbiniDetails->stores->lawson->payment_code }}</li>
                                    <li>確認番号: {{ $konbiniDetails->stores->lawson->confirmation_number }}</li>
                                    <li>支払い期日: {{ \Carbon\Carbon::parse($konbiniDetails->expires_at)->format('Y-m-d H:i') }}</li>
                                </ul>
                            </div>
                            <div id="ministop" class="tab-content__panel">
                                <ul>
                                    <li>支払いコード: {{ $konbiniDetails->stores->ministop->payment_code }}</li>
                                    <li>確認番号: {{ $konbiniDetails->stores->ministop->confirmation_number }}</li>
                                    <li>支払い期日: {{ \Carbon\Carbon::parse($konbiniDetails->expires_at)->format('Y-m-d H:i') }}</li>
                                </ul>
                            </div>
                            <div id="seicomart" class="tab-content__panel">
                                <ul>
                                    <li>支払いコード: {{ $konbiniDetails->stores->seicomart->payment_code }}</li>
                                    <li>確認番号: {{ $konbiniDetails->stores->seicomart->confirmation_number }}</li>
                                    <li>支払い期日: {{ \Carbon\Carbon::parse($konbiniDetails->expires_at)->format('Y-m-d H:i') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="item--back-button">
        <a href="/" class="edit-button">TOPページへ戻る</a>
    </div>
</div>
@endsection