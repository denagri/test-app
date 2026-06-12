<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-logo">
            <img src="{{ asset('storage/header_logo.png') }}" alt="Logo">
        </div>
        <form class="header-search" action="/search" method="get">
            <input type="hidden" name="tab"  value="{{ $tab ??'recommend' }}"/>
            <input type="text" name="keyword" class="search-box" value="{{ $keyword ??''}}" placeholder="    なにをお探しですか？">
        </form>
        <div class="header-button">
            @auth
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="log">ログアウト</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="log">ログイン</a>
            @endauth
            <a href="{{ route('mypage') }}" class="mypage">マイページ</a>
            <a href="{{ route('listing') }}" class="listing">出品</a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>