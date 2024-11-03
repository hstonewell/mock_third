@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css')}}">
@endsection

@section('main')
<div class="register-form">
    <div class="register-form__header">
        <h2 class="register-form__header--title">ログイン</h2>
    </div>
    <div class="register-form__content">
        <form class="login" method="POST" action="/login">
            @csrf
            <div class="register-form__item">
                <label class="register-form--label">メールアドレス</label>
                <input class="register-form--input" type="text" name="email" id="email" value="{{ old('email') }}">
            </div>
            @if ($errors->has('email'))
            @foreach($errors->get('email') as $message)
            <p class="form--error-message">
                {{ $message }}
            </p>
            @endforeach
            @endif
            <div class="register-form__item">
                <label class="register-form--label">パスワード</label>
                <input class="register-form--input" type="password" name="password" id="password" value="{{ old('password') }}">
            </div>
            @if ($errors->has('password'))
            @foreach($errors->get('password') as $message)
            <p class="form--error-message">
                {{ $message }}
            </p>
            @endforeach
            @endif
            <div class="register-form__item">
                <input class="submit-button" type="submit" value="ログイン">
                <a href="/register" class="register-form--link">会員登録はこちら</a>
            </div>
        </form>
    </div>
    @endsection