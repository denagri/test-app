<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-logo">
            <image>
        </div>
        <div class="header-search">
            <input type="text" value="なにをお探しですか？" class="search-box">
        </div>
        <div class="header-button">
            @auth
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="log">ログアウト</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="log">ログイン</a>
            @endauth
            <a href="{{ url('/mypage') }}" class="mypage">マイページ</a>
            <a href="{{ url('/purchase') }}" class="listing">出品</a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>