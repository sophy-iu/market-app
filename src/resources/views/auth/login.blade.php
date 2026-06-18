@extends('layouts/auth-header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css')}}">
@endsection

@section('content')
<div class="login">
    <h2 class="login__heading">ログイン</h2>
    <div class="login__inner">
        <form class="login-form" action="/login" method="POST">
            @csrf
            <div class="login-form__group">
                <label class="login-form__label" for="email">メールアドレス</label>
                <input class="login-form__input" type="email" name="email" id="email">
                <p class="login-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <label class="login-form__label" for="password">パスワード</label>
                <input class="login-form__input" type="password" name="password" id="password">
                <p class="login-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input class="login-form__btn" type="submit" value="登録する">
        </form>
    </div>
</div>
@endsection

@section('link')
<a class="auth-link" href="{{ route('register') }}">会員登録はこちら</a>
@endsection