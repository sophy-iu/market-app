<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield ('css')
</head>
<body>
    <header class="header">
        <h1 class="header__logo">
            <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH">
        </h1>
        <div class="header__inner">
            <form action="/" class="header__search-form" method="GET">
                <input type="text" class="header__search-form__keyword" name="keyword" placeholder="なにをお探しですか？" value="{{request('keyword')}}">
            </form>
            <nav class="header__nav">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <input class="header__nav-button" type="submit" value="ログアウト">
                    </form>
                @endauth
                @guest
                    <a class="header__nav-link" href="{{ route('login') }}">ログイン</a>
                @endguest
                <a class="header__nav-link" href="/mypage">マイページ</a>
                <a class="header__nav-sell" href="/sell">出品</a>
            </nav>
        </div>
    </header>
    <div class="content">
        @yield('content')
    </div>
    <div class="link">
        @yield('link')
    </div>
</body>
</html>