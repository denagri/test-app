<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/register.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header-logo">
            <image src="{{ asset('storage/header_logo.png') }}" alt="Logo">
        </div>
    </header>

    <main>
        <form class="register-box" action="{{ route('register.step1.post') }}" method="post">
            @csrf
            <h1>会員登録</h1>
            <div class="register-input-box">
                <ul>
                    <li>ユーザー名</li>
                    <li>
                        <input type="text" name="name" value="{{ old('name') }}">
                    </li>
                    <li>
                        <div class="form-error">
                            @error('name')
                            <div style="color:red;">{{ $message }}</div>
                            @enderror
                        </div>
                    </li>
                </ul>
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
                                @if($message !=='パスワードと一致しません')
                                <div style="color:red;">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>
                    </li>
                </ul>
                <ul>
                    <li>確認用パスワード</li>
                    <li>
                        <input type="password" name="password_confirmation">
                    </li>
                    <li>
                        <div class="form-error">
                            @error('password')
                                @if($message ==='パスワードと一致しません')
                                <div style="color:red;">{{ $message }}</div>
                                @endif
                            @enderror
                        </div>
                    </li>
                </ul>
            </div>
            <div class="register-form">
                <button type="submit" class="register-btn">登録する</button>
                <a href="/login">ログインはこちら</a>
            </div>
        </form>
    </main>
</body>
</html>