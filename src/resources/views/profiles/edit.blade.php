@extends('layouts/app-header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css')}}">
@endsection

@section('content')
<div class="profile-edit">
    <h2 class="profile-edit__title">プロフィール設定</h2>
    <div class="profile-edit__inner">
        <form action="/mypage/profile" class="profile-edit__form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="profile-edit__image-area">
                <img src="{{ $profile->image ? asset('storage/'.$profile->image) : '' }}" class="profile-edit__img" alt=""/>
                <label class="profile-edit__image-label" for="image">画像を選択する</label>
                <input class="profile-edit__image-input" type="file" name="image" id="image" accept=".jpeg,.png">
            </div>
            @error('image')
                <p class="profile-form__error">{{ $message }}</p>
            @enderror
            <div class="profile-edit__group">
                <label class="profile-edit__label" for="name">ユーザー名</label>
                <input class="profile-edit__input" type="text" name="name" id="name" value="{{ $profile->name ?? '' }}">
            </div>
            @error('name')
                <p class="profile-form__error">{{ $message }}</p>
            @enderror
            <div class="profile-edit__group">
                <label class="profile-edit__label" for="postal_code">郵便番号</label>
                <input class="profile-edit__input" type="text" name="postal_code" id="postal_code" value="{{ $profile->postal_code ?? '' }}">
            </div>
            @error('postal_code')
                <p class="profile-form__error">{{ $message }}</p>
            @enderror
            <div class="profile-edit__group">
                <label class="profile-edit__label" for="address">住所</label>
                <input class="profile-edit__input" type="text" name="address" id="address" value="{{ $profile->address ?? '' }}">
            </div>
            @error('address')
                <p class="profile-form__error">{{ $message }}</p>
            @enderror
            <div class="profile-edit__group">
                <label class="profile-edit__label" for="building">建物名</label>
                <input class="profile-edit__input" type="text" name="building" id="building" value="{{ $profile->building ?? '' }}">
            </div>
            <input class="profile-edit__btn btn" type="submit" value="更新する">
        </form>
    </div>
</div>
@endsection