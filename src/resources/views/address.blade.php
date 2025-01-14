@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/form.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="edit-form__header">
        <h1>住所の変更</h1>
    </div>
    <div class="edit-form">
        <form method="post" action="{{ route('address.create', ['item_id' => $item->id]) }}" class="edit-form__input">
            @csrf
            <div class="edit-form__unit">
                <label class="edit-form__input--label">郵便番号</label>
                <input class="edit-form__input--input" type="text" name="postcode" value="{{ old('postcode', Auth::user()->userProfile->postcode ?? '') }}" />
                @if ($errors->has('postcode'))
                @foreach($errors->get('postcode') as $message)
                <p class="form--error-message">
                    {{ $message }}
                </p>
                @endforeach
                @endif
            </div>
            <div class="edit-form__unit">
                <label class="edit-form__input--label">住所</label>
                <input class="edit-form__input--input" type="text" name="address" value="{{ old('address', Auth::user()->userProfile->address ?? '') }}" />
                @if ($errors->has('address'))
                @foreach($errors->get('address') as $message)
                <p class="form--error-message">
                    {{ $message }}
                </p>
                @endforeach
                @endif
            </div>
            <div class="edit-form__unit">
                <label class="edit-form__input--label">建物名</label>
                <input class="edit-form__input--input" type="text" name="building" value="{{ old('building', Auth::user()->userProfile->building ?? '') }}" />
                @if ($errors->has('building'))
                @foreach($errors->get('building') as $message)
                <p class="form--error-message">
                    {{ $message }}
                </p>
                @endforeach
                @endif
            </div>
            <button type="submit" class="submit-button">更新する</button>
        </form>
    </div>
</div>
@endsection