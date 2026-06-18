@extends('layouts/app-header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
@endsection

@section('content')
<div class="item-content">
    @foreach($items as $item)
        <img src="{{ asset($item->image) }}" alt="{{ $item->item_name }}" class="item-card__img" />
        <div class="item-basic">
            <p class="item-basic__name">{{$item->item_name}}</p>
            <p class="item-basic__bland-name">{{$item->item_bland_name}}</p>
            <p class="item-basic__price">¥{{$item->price}}</p>
        </div>
        <div class="item-icon">
            @if($items->likes)
                <img src="{{ asset('images/ハートロゴ_ピンク.png') }}" alt="" class="item-icon__likes" />
                <p class="item-icon__likes__number">{{$item->like}}</p>
            @else
                <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="" class="item-icon__likes" />
                <p class="item-icon__likes__number">{{$item->like}}</p>
            @endif
            <img src="{{ asset('images/ふきだしロゴ.png') }}" alt="" class="item-icon__comment" />
            <p class="item-icon__comment-number">{{$item->comment_number}}</p>
        </div>
        <a class="item-link" href="/purchase/{item->id}">購入手続きへ</a>
        <div class="item-detail">
            <p class="item-detail__tag">商品説明</p>
            <p class="item-detail__explanation">{{$item->item_description}}</p>
        </div>
        <div class="item-information">
            <p class="item-information__tag">商品の情報</p>
            <p class="item-information__category">カテゴリー{{$item->category}}</p>
            <p class="item-information__condition">商品の状態{{$item->condition}}</p>
        </div>
        <div class="item-commnet">
            <p class="item-commnet__tag">コメント({{$item->comment_number}})</p>
            <p class="item-commnet__">{{$item->comment_description}}</p>
            <form action="" method="GET">
                <p class="item-commnet__tag">商品へのコメント</p>
                <textarea name="comments" id=""></textarea>
                <button class="item-commnet__submit" type="submit">コメントを送信する</button>
            </form>
        </div>
    @endforeach
</div>
@endsection