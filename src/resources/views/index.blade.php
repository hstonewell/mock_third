@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/index.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__inner__tab-wrapper">
        <input id="tab-recommend" type="radio" name="tab_button" checked>
        <input id="tab-mylist" type="radio" name="tab_button">
        <div class="main__inner--tab-labels">
            <label class="label-recommend" for="tab-recommend">おすすめ</label>
            <label class="label-mylist" for="tab-mylist">マイリスト</label>
        </div>
        <div class="main__inner__tab-content">
            <div id="tab-panel-recommend" class="tab-panel">
                <div class="tab-panel__inner">
                    <div class="item-thumbnail"><img src="item-thumbnail"></div>
                    <div class="item-thumbnail"><img src="item-thumbnail"></div>
                    <div class="item-thumbnail"><img src="item-thumbnail"></div>
                    <div class="item-thumbnail"><img src="item-thumbnail"></div>
                    <div class="item-thumbnail"><img src="item-thumbnail"></div>
                </div>
            </div>
            <div id="tab-panel-mylist" class="tab-panel">
                <div class="tab-panel__inner">
                    <div class="item-thumbnail"><img src="item-thumbnail"></div>
                    <div class="item-thumbnail"><img src="item-thumbnail"></div>
                    <div class="item-thumbnail"><img src="item-thumbnail"></div>
                    <div class="item-thumbnail"><img src="item-thumbnail"></div>
                    <div class="item-thumbnail"><img src="item-thumbnail"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection