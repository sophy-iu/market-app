@extends('layouts/app-header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="item-tab">
    <a href="/?keyword={{ request('keyword') }}" class="item-tab__link {{ request('tab') != 'mylist' ? 'active' : '' }}" >おすすめ</a>
    <a href="/?tab=mylist&keyword={{ request('keyword') }}" class="item-tab__link {{ request('tab') == 'mylist' ? 'active' : '' }}" >マイリスト</a>
</div>
<div class="item-content">
    @foreach($items as $item)
        <a href="/item/{item->id}" class="item-link">
            <div class="item-card">
                @if($item->purchase)
                    <span class="item-card__sold">Sold</span>
                @endif
                <img src="{{ asset($item->image) }}" alt="{{ $item->item_name }}" class="item-card__img" />
                <p class="item-card__name">{{$item->item_name}}</p>
            </div>
        </a>
    @endforeach
</div>
@endsection