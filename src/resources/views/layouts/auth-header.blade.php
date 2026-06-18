<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield ('css')
</head>
<body>
    <header class="header">
        <h1 class="header__logo">
            <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH">
        </h1>
    </header>
    <div class="content">
        @yield('content')
    </div>
    <div class="link">
        @yield('link')
    </div>
</body>
</html>