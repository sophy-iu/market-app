@extends('layouts/app-header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('content')
<form class="purchase" action="/purchase/{{ $item->id }}" method="POST">
    @csrf
    <div class="purchase__left">
        <div class="item-detail">
            <img src="{{ asset($item->image) }}" alt="{{ $item->item_name }}" class="item-detail__img" />
            <h2 class="item-detail__name">{{$item->item_name}}</h2>
            <p class="item-detail__price">¥{{ number_format($item->price) }}</p>
        </div>
        <div class="payment-method">
            <h3 class="payment-method__tag">支払い方法</h3>
            <select id="payment_method" name="payment_method" class="payment-method__select" required>
                <option value="" disabled selected>選択してください</option>
                <option value="convenience_store">コンビニ払い</option>
                <option value="card">カード払い</option>
            </select>
        </div>
        <div class="address">
            <div class="address__inner">
                <h3 class="address__tag">配送先</h3>
                <a href="/purchase/address/{{ $item->id }}" class="address__link">変更する</a>
            </div>
            @php
                $purchaseAddress = session('purchase_address');
                $profile = auth()->user()->profile;
            @endphp
            <p class="address__confirmation">
                〒{{ $purchaseAddress['postal_code'] ?? $profile->postal_code }}<br>
                {{ $purchaseAddress['address'] ?? $profile->address }}
                {{ $purchaseAddress['building'] ?? $profile->building }}
            </p>
        </div>
    </div>
    <div class="purchase__right">
        <div class="purchase-confirm">
            <div class="purchase-confirm__row">
                <span>商品代金</span>
                <span>¥{{ number_format($item->price) }}</span>
            </div>
            <div class="purchase-confirm__row">
                <span>支払い方法</span>
                <span id="selected-payment-method"></span>
            </div>
        </div>
        <button class="purchase__button" type="submit">
            購入する
        </button>
    </div>
</form>
<script>
    const paymentSelect = document.getElementById('payment_method');
    const paymentText = document.getElementById('selected-payment-method');
    paymentSelect.addEventListener('change', function () {
        const selectedOption = paymentSelect.options[paymentSelect.selectedIndex];
        paymentText.textContent = selectedOption.text;
    });
</script>
@endsection