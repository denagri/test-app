<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header-logo">
            <image src="{{ asset('storage/header_logo.png') }}" alt="Logo">
        </div>
    </header>

    <main>
        <form class="login-box" action="/login" method="post">
            @csrf
            <h1>ログイン</h1>
            <div class="login-input-box">
                <ul>
                    <li>メールアドレス</li>
                    <li>
                        <input type="text" name="email" value="{{ old('email') }}">
                    </li>
                    <li>
                        <div class="form-error">
                            @error('email')
                            <div style="color:red;">{{ $message }}</div>
                            @enderror
                        </div>
                    </li>
                </ul>
                <ul>
                    <li>パスワード</li>
                    <li>
                        <input type="password" name="password">
                    </li>
                    <li>
                        <div class="form-error">
                            @error('password')
                            <div style="color:red;">{{ $message }}</div>
                            @enderror
                            @error('auth_failed')
                                <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>
                    </li>
                </ul>
            </div>
            <div class="login-form">
                <button type="submit" class="login-btn">ログインする</button>
                <a href="/register">会員登録はこちら</a>
            </div>
        </form>
    </main>
</body>
</html>