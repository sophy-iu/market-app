@extends('layouts/auth-header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css')}}">
@endsection

@section('content')
<div class="address">
    <h2 class="address__heading">住所の変更</h2>
    <div class="address__inner">
        <form class="address-form" action="/purchase/address/{{ $item->id }}" method="POST">
            @csrf
            <div class="address-form__group">
                <label class="address-form__label" for="postal_code">郵便番号</label>
                <input class="address-form__input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $postalCode) }}">
            </div>
            @error('postal_code')
                <p class="address-form__error">{{ $message }}</p>
            @enderror
            <div class="address-form__group">
                <label class="address-form__label" for="address">住所</label>
                <input class="address-form__input" type="text" name="address" id="address" value="{{ old('address', $address) }}">
            </div>
            @error('address')
                <p class="address-form__error">{{ $message }}</p>
            @enderror
            <div class="address-form__group">
                <label class="address-form__label" for="building">建物名</label>
                <input class="address-form__input" type="text" name="building" id="building" value="{{ old('building', $building) }}">
            </div>
            <input class="address-form__btn" type="submit" value="更新する">
        </form>
    </div>
</div>
@endsection