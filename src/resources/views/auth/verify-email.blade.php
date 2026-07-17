@extends('layouts.auth-header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <p class="verify-email__message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <a class="verify-email__link" href="http://localhost:8025" target="_blank" rel="noopener noreferrer">
        認証はこちらから
    </a>

    <form class="verify-email__resend-form" action="{{ route('verification.send') }}" method="POST">
        @csrf
        <button class="verify-email__resend-button" type="submit">認証メールを再送する</button>
    </form>

    @if(session('status') === 'verification-link-sent')
        <p class="verify-email__status">新しい認証メールを送信しました。</p>
    @endif
</div>
@endsection