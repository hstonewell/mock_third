@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/dashboard.css')}}">
<link rel="stylesheet" href="{{ asset('/css/form.css')}}">
@endsection

@section('header')
<div class="header__inner--logo">
    <a href="{{ route('index') }}"><img src="{{ asset('img/logo.svg') }}" alt="COACHTECHフリマ"></a>
</div>
<div class="header__inner--menu">
    @if (Auth::check())
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="header__link">ログアウト</button>
    </form>
    @endif
</div>
@endsection

@section('main')
@if(session('success'))
<div class="success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="error">{{ session('error') }}</div>
@endif
<div class="dashboard">
    <div class="users-table__header">
        <h1>ユーザ一覧</h1>
    </div>
    <div class="users-table">
        <table>
            <tr>
                <th>ID</th>
                <th>ユーザ名</th>
                <th>メールアドレス</th>
                <th class="mail"></th>
                <th>削除</th>
            </tr>
            @foreach($users as $user)
            <tr>
                <td>
                    <p>{{ $user->id }}</p>
                </td>
                <td>
                    <p>{{ $user->userProfile->name }}</p>
                </td>
                <td>
                    <p>{{ $user->email }}</p>
                </td>
                <td>
                    <livewire:send-email :email="$user->email" :userName="$user->userProfile->name" />
                </td>
                <td>
                    <form method="POST" action="{{ route('user.destroy', ['user_id' => $user->id]) }}">
                        @csrf
                        <button class="submit-button">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
        <div class="users-table__paginate">{{ $users->links() }}</div>
    </div>
</div>

@endsection