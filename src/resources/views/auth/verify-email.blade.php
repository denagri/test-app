@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mail.css') }}" />
@endsection

@section('content')
<div class="mail-box">
    <div class="mail-text">登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。
    </div>
    <div class="certification-box">
        <a href="http://localhost:8025" target="_blank" class="certification-btn">認証はこちらから</a>
    </div>
    <form method="post" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="mail-resend">認証メールを再送する</button>
    </form>
</div>
@endsection