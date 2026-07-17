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
                <input class="item-img__input" type="file" name="image" id="image" accept=".jpeg,.png">
            </div>
            @error('image')
                <p class="sell-form__error">{{ $message }}</p>
            @enderror
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
                @error('category_id')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
                <h4 class="item-detail__condition-tag">商品の状態</h4>
                <select id="condition_id" name="condition_id" class="item-condition__select  {{ old('condition_id') ? 'is-selected' : '' }}" required onchange="this.classList.toggle('is-selected', this.value !== '')">
                    <option value="" disabled  {{ old('condition_id') ? '' : 'selected' }}>選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                    @endforeach
                </select>
                @error('condition_id')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="item-infomation">
                <h3 class="item-infomation__title">商品名と説明</h3>
                <h4 class="item-infomation__tag">商品名</h4>
                <input type="text" name="item_name" class="item-infomation__input">
                @error('item_name')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
                <h4 class="item-infomation__tag">ブランド名</h4>
                <input type="text" name="brand_name" class="item-infomation__input">
                <h4 class="item-infomation__tag">商品の説明</h4>
                <textarea name="item_description" class="item-infomation__textarea"></textarea>
                @error('item_description')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
                <h4 class="item-infomation__tag">販売価格</h4>
                <div class="item-infomation__price">
                    <span class="item-infomation__price-mark">¥</span>
                    <input type="text" name="price" class="item-infomation__price-input">
                </div>
                @error('price')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="submit">出品する</button>
        </form>
    </div>
</div>
@endsection