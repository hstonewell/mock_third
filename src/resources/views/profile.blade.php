@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/form.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="edit-form__header">
        <h1>プロフィール設定</h1>
    </div>
    <div class="edit-form">
        <livewire:profile-form>
    </div>
</div>
@endsection