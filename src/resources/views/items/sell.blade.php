@extends('layouts/app-header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css')}}">
@endsection

@section('content')
<div class="sell">
    <h2 class="sell__title">商品の出品</h2>
    <div class="item">
        <form action="/sell" class="item-form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="item-img">
                <h4 class="item-img__tag">商品画像</h4>
                <label class="item-img__label" for="image">画像を選択する</label>
                <input class="item-img__input" type="file" name="image" id="image">
                <img src="{{ $profile->image ? asset('storage/' . $profile->image) }}" class="item-img img" alt="商品画像"/>
            </div>
            <div class="item-detail">
                <h3 class="item-detail__title">商品の詳細</h3>
                <h4 class="item-detail__tag">カテゴリー</h4>
                <div class="item-detail__categories">
                    @foreach($categories as $category)
                        <label class="item-detail__category">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
                <h4 class="item-detail__condition-tag">商品の状態</h4>
                <select name="condition_id" class="item-condition__select" required>
                    <option value="" disabled selected>選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="item-infomation">
                <h3 class="item-infomation__title">商品名と説明</h3>
                <h4 class="item-infomation__tag">商品名</h4>
                <input type="text" name="item_name" class="item-infomation__input">
                <h4 class="item-infomation__tag">ブランド名</h4>
                <input type="text" name="brand_name" class="item-infomation__input">
                <h4 class="item-infomation__tag">商品の説明</h4>
                <textarea name="item_description" class="item-infomation__textarea"></textarea>
                <h4 class="item-infomation__tag">販売価格</h4>
                <input type="text" name="price" class="item-infomation__input">¥
            </div>
            <button type="submit" class="submit">出品する</button>
        </form>
    </div>
</div>
@endsection