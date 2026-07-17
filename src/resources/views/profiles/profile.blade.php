@extends('layouts/app-header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css')}}">
@endsection

@section('content')
<div class="profile">
    <img src="{{ $profile->image ? asset('storage/' . $profile->image) : asset('images/default-user.png') }}" class="profile__img" alt="" />
    <p class="profile__name">{{ $user->profile->name ?? $user->name }}</p>
    <a href="/mypage/profile" class="profile__edit-link">プロフィールを編集</a>
</div>
<div class="mypage-tab">
    <a href="/mypage?page=sell" class="mypage-tab__link {{ request('page') !== 'buy' ? 'active' : '' }}" >出品した商品</a>
    <a href="/mypage?page=buy" class="mypage-tab__link {{ request('page') == 'buy' ? 'active' : '' }}" >購入した商品</a>
</div>
<div class="item">
    @foreach($items as $item)
        <a href="/item/{{ $item->id }}" class="item-link">
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