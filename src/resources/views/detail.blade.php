@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
@endsection

@section('content')
<div class="detail-box">
    <div class="detail-image">
        <img src="{{ asset('storage/'.$product->image_path) }}" class="item-image" alt="{{ $product->name }}">
    </div>
    <div class="item-main">
        <h1>{{ $product->name }}</h1>
        <p class="brand-name">{{ $product->brand }}</p>
        <div class="price-box">
            <h2>￥{{ number_format($product->price) }}</h2>
            <h3>（税込）</h3>
        </div>
        <div class="icon-box">
            <div class="good-box">
            @auth
                <form action="{{ route('like.toggle',$product->id) }}" method="post" id="likeForm">
                @csrf
                    <button type="submit" class="good-btn">
                    @if(Auth::check() && $product->likes->where('user_id', Auth::id())->isNotEmpty())
                        <img src="{{ asset('storage/heart_pink.png') }}" >
                    @else
                        <img src="{{ asset('storage/heart.png') }}" >
                    @endif
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="good-btn">
                    <img src="{{asset('storage/heart.png') }}" >
                </a>
            @endauth
                <p class="good-count">{{ $product->likes_count }}</p>
            </div>
            <div class="comment-box">
                <img src="{{ asset('storage/talk.png') }}" alt="comment">
                <p class="comment-count">{{ $product->comments_count }}</p>
            </div>
        </div>
        @auth
            <a href="{{ route('purchase',$product->id) }}" class="purchase-btn">購入手続きへ</a>
        @else
            <a href="{{ route('login') }}" class="purchase-btn">ログインして購入する</a>
        @endauth
        <div class="item-explanation">
            <h3>商品説明</h3>
            <div class="explanation">{{($product->explanation)}}</div>
        </div>
        <div class="item-info-box">
            <h3>商品の情報</h3>
            <div class="item-category">
                <h4>カテゴリー</h4>
                <div class="tag-group">
                    @foreach($product->categories as $category)
                    <span class="category-tag">
                        {{ $category->kind}}
                    </span>
                    @endforeach
                </div>
            </div>
            <div class="item-condition">
                <h4>商品の状態</h4>
                <div class="condition-kind">
                    {{ $product->condition->kind}}
                </div>
            </div>
        </div>
        <div class="item-comment">
            <h3 class="comment">コメント ({{ $product->comments_count }}) </h3>
            <div class="comment-list">
                @foreach($product->comments as $comment)
                <div class="comment-item">
                    <div class="comment-user">
                        <image class="profile-image" src="{{ asset('storage/profile.png') }}">
                        <p>{{ $comment->user->name }}</p>
                    </div>
                    <div class="comment-content">{{ $comment->content }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @auth
        <form action="{{ route('comment.store',['item_id' => $product->id]) }}" method="post" class="comment-form">
            @csrf
            <div class="comment-input">商品へのコメント</div>
            <textarea name="content" class="textbox" cols="73" rows="8">{{ old('content') }}</textarea>
            <button type="submit" class="comment-btn">コメントを送信する</button>
            <div class="form-error">
                @error('content')
                <div style="color:red;">{{ $message }}</div>
                @enderror
            </div>
        </form>
        @else
        <div class="comment-form">
            <div class="comment-input">商品へのコメント</div>
            <textarea name="content" class="textbox" cols="73" rows="8"></textarea>
            <a href="{{ route('login') }}" class="guest-comment-btn">コメントを送信する
            </a>
        </div>
        @endauth
    </div>
</div>
<script>
function toggleImage() {
    const img = document.getElementById('goodImage');
    const whiteHeart ="{{ asset('storage/heart.png') }}";
    const pinkHeart ="{{ asset('storage/heart_pink.png') }}";
    if (img.src.includes("heart_pink.png")) {
        img.src = whiteHeart;
    } else {
        img.src = pinkHeart;
    }
}
</script>
@endsection
