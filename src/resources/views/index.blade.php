@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/index.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__inner__tab-wrapper">
        <input id="tab-recommend" type="radio" name="tab-button" checked>
        <input id="tab-mylist" type="radio" name="tab-button">
        <div class="main__inner--tab-labels">
            <label class="label-recommend" for="tab-recommend">おすすめ</label>
            <label class="label-mylist" for="tab-mylist">マイリスト</label>
        </div>
        <div class="main__inner__tab-content">
            <div id="tab-panel-recommend" class="tab-panel">
                <div class="tab-panel__inner">
                    @foreach ($recommendItems as $recommendItem)
                    <div class="tab-panel--item-thumbnail">
                        <a href="{{ route('item.detail', ['item_id'=>$recommendItem->id]) }}"><img src="{{ asset($recommendItem->image) }}" alt="{{ $recommendItem->item_name }}"></a>
                    </div>
                    @endforeach
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