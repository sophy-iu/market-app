@extends('layouts/app-header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
@endsection

@section('content')
<div class="item-detail">
    <div class="item-detail__image">    
        <img src="{{ asset($item->image) }}" alt="{{ $item->item_name }}" class="item-detail__img" />
    </div>
    <div class="item-detail__content">
        <div class="item-basic">
            <h2 class="item-basic__name">{{$item->item_name}}</h2>
            <p class="item-basic__brand-name">{{$item->item_brand_name}}</p>
            <p class="item-basic__price">¥{{ number_format($item->price) }}（税込）</p>
        </div>
        <div class="item-detail__icons">
            @auth
            <form action="/item/{{ $item->id }}" method="POST">
                @csrf
                <button type="submit" class="item-detail__like-button">
                    @if(auth()->check() && $item->likes->contains('user_id', auth()->id()))
                        <img src="{{ asset('images/ハートロゴ_ピンク.png') }}" alt="いいね済み" class="item-detail__like">
                    @else
                        <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="いいね" class="item-detail__like">
                    endif
                </button>
                <p class="item-detail__like-count">{{ $item->likes->count() }}</p>
            </form>
            @endauth
            @guest
            <a href="{{ route('login') }}">
                <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="いいね" class="item-detail__like">
            </a>
            <p class="item-detail__like-count">{{ $item->likes->count() }}</p>
            @endguest
            <img src="{{ asset('images/ふきだしロゴ.png') }}" alt="" class="item-detail__comment" />
            <p class="item-detail__comment-count">{{ $item->comments->count() }}</p>
        </div>
        <a class="item-detail__purchase-link" href="/purchase/{{ $item->id }}">購入手続きへ</a>
        <div class="item-detail">
            <h3 class="item-detail__section-title">商品説明</h3>
            <p class="item-detail__description">{{$item->item_description}}</p>
        </div>
        <div class="item-detail__info">
            <h3 class="item-info__tag">商品の情報</h3>
            <p class="item-detail__category">
                カテゴリー
                @foreach ($item->categories as $category)
                    <span>{{ $category->name }}</span>
                @endforeach
            </p>
            <p class="item-detail__condition">商品の状態 {{ $item->condition->name }}</p>
        </div>
        <div class="item-comment">
            <h3 class="item-comment__title">コメント({{ $item->comments->count() }})</h3>
            @foreach($item->comments as $comment)
                <div class="item-comment__content">
                    <p class="item-comment__user">{{ $comment->user->name }}</p>
                    <p class="item-comment__text">{{ $comment->comment }}</p>
                </div>
            @endforeach
            @auth
            <form action="/item/{{ $item->id }}" method="POST">
                @csrf
                <p class="item-comment__tag">商品へのコメント</p>
                <textarea name="comment" class="item-comment__textarea"></textarea>
                <button class="item-comment__button" type="submit">コメントを送信する</button>
            </form>
            @endauth
            @guest
            <a href="{{ route('login') }}" class="item-comment__button">コメントを送信する</a>
            @endguest
        </div>
    </div>
</div>
@endsection