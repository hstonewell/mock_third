@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/profile.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="edit-profile__header">
        <h2>プロフィール設定</h2>
    </div>
    <div class="edit-profile__form">
        <livewire:image-upload>
            <form method="post" action="{{ route('profile.create') }}">
                @csrf
                <div class=" edit-profile__form--input">
                    <label>ユーザー名</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->userProfile->name ?? '') }}" />
                    @if ($errors->has('name'))
                    @foreach($errors->get('name') as $message)
                    <p class="form--error-message">
                        {{ $message }}
                    </p>
                    @endforeach
                    @endif
                </div>
                <div class="edit-profile__form--input">
                    <label>郵便番号</label>
                    <input type="text" name="postcode" value="{{ old('postcode', Auth::user()->userProfile->postcode ?? '') }}" />
                    @if ($errors->has('postcode'))
                    @foreach($errors->get('postcode') as $message)
                    <p class="form--error-message">
                        {{ $message }}
                    </p>
                    @endforeach
                    @endif
                </div>
                <div class="edit-profile__form--input">
                    <label>住所</label>
                    <input type="text" name="address" value="{{ old('address', Auth::user()->userProfile->address ?? '') }}" />
                    @if ($errors->has('address'))
                    @foreach($errors->get('address') as $message)
                    <p class="form--error-message">
                        {{ $message }}
                    </p>
                    @endforeach
                    @endif
                </div>
                <div class="edit-profile__form--input">
                    <label>建物名</label>
                    <input type="text" name="building" value="{{ old('building', Auth::user()->userProfile->building ?? '') }}" />
                    @if ($errors->has('building'))
                    @foreach($errors->get('building') as $message)
                    <p class="form--error-message">
                        {{ $message }}
                    </p>
                    @endforeach
                    @endif
                </div>
                <button type="submit" class="submit-button" >更新する</button>
            </form>
    </div>
</div>
@endsection