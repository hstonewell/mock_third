@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/sell.css')}}">
<link rel="stylesheet" href="{{ asset('/css/form.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="edit-form__header">
        <h2>商品の出品</h2>
    </div>
    <div class="edit-form">
        <livewire:item-selling-form>
    </div>
</div>
@endsection