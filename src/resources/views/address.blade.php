@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/address.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="edit-address__header">
        <h2>住所の変更</h2>
    </div>
    <div class="edit-address__form">
        <form method="post" action="">
            @csrf
            <div class="edit-address__form--input">
                <label>郵便番号</label>
                <input type="text" pattern="[0-9]{7}" name="postcode" />
            </div>
            <div class="edit-address__form--input">
                <label>住所</label>
                <input type="text" name="address" />
            </div>
            <div class="edit-address__form--input">
                <label>建物名</label>
                <input type="text" name="building" />
            </div>
            <button type="submit" class="submit-button">更新する</button>
        </form>
    </div>
</div>
@endsection