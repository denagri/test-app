@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
@endsection

@section('content')
<div class="profile-title">
    <image class="profile-image" src="{{ asset('storage/profile.png') }}">
    <h2>{{ Auth::user()->name }}</h2>
    <a href="{{ route('profile.update') }}" class="edit-btn">プロフィールを編集</a>
</div>
<div class="profile-box">
    <div class="icon-box">
        <a href="{{ route('mypage', ['tab' => 'sell']) }}" 
            class="listing-icon {{ $tab !== 'buy' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ route('mypage', ['tab' => 'buy']) }}" 
            class="purchase-icon {{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>
</div>
<div class="goods-box">
    @foreach($products as $product)
    <a href="{{ route('items.show', ['item_id' => $product->id]) }}" class="item-detail">
        <div class="item-box">
            <image src="{{ asset('storage/'.$product->image_path) }}" class="item-image" alt="画像">
            <p class="item-name">{{ $product->name }}</p>
        </div>
    </a>
    @endforeach
</div>

@endsection