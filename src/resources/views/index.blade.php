@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}" />
@endsection

@section('content')
<div class="index-box">
    <div class="icon-box">
        <a href="{{ route('index',['tab' => 'recommend','keyword'=>$keyword ??'']) }}" class="recommend-icon {{ $tab !=='mylist' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('index', ['tab' => 'mylist','keyword'=>$keyword ??'']) }}" class="my-list-icon {{ $tab ==='mylist' ? 'active' : '' }}">マイリスト</a>
    </div>
</div>
<div class="goods-box">
    @foreach($products as $product)
    <a href="/item/{{ $product->id }}">
        <div class="item-box">
            <img src="{{ asset('storage/'.$product->image_path) }}" class="item-image" alt="{{ $product->name }}">
            <div class="item-info">
                @if($product->purchase)
                <div class="sold-label">
                    SOLD
                </div>
                @endif
                <p class="item-name">{{ $product->name }}</p>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection
