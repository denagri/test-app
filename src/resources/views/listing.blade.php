@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/listing.css') }}" />
@endsection

@section('content')
<form class="listing_box" action="{{ route('listing.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <h1>商品の出品</h1>
    <div class="image-box">
        <h3>商品画像</h3>
        <div class="image">
            <button type="button" class="image-btn" onclick="document.getElementById('image-input').click();">画像を選択する</button>
            <input type="file" id="image-input" name="image" accept="image/*" style="display: none;" onchange="previewImage(this);">
        </div>
        <div class="form-error">
            @error('image')
            <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <h2>商品の詳細</h2>
    <div class="detail-box">
        <h3>カテゴリー</h3>
        <div class="category-group">
            @foreach($categories as $category)
            <label class="category-label">
                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="category-input">
                <span class="category-tag">{{ $category->kind}}</span>
            </label>
            @endforeach
        </div>
        <div class="form-error">
            @error('category_ids')
            <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>
        <h3>商品の状態</h3>
        <div class="method-box">
            <select name="condition_id" class="input-condition">
                <option value="" disabled selected>選択してください</option>
                @foreach($conditions as $condition)
                <option value="{{ $condition->id }}">{{ $condition->kind }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-error">
            @error('condition_id')
            <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <h2>商品名と説明</h2>
    <div class="explain-box">
        <ul>
            <li>商品名</li>
            <li>
                <input type="text" name="name" class="input-line" value="{{ old('name') }}">
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
            <li>ブランド名</li>
            <li>
                <input type="text" name="brand" class="input-line" value="{{ old('brand') }}">
            </li>
            <li>
                <div class="form-error">
                    @error('brand')
                    <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>
            </li>
        </ul>
        <ul>
            <li>商品の説明</li>
            <li>
                <textarea name="explanation" class="input-line" cols="30" rows="5"></textarea>
            </li>
            <li>
                <div class="form-error">
                    @error('explanation')
                    <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>
            </li>
        </ul>
        <ul>
            <li>販売価格</li>
            <li>
                <div class="price-input-box">
                    <span class="price-unit">￥</span>
                    <input type="number" name="price" class="input-line" value="{{ old('price') }}">
                </div>
            </li>
            <li>
                <div class="form-error">
                    @error('price')
                    <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>
            </li>
        </ul>
        <div class="update-form">
            <button type="submit" class="update-btn">出品する</button>
        </div>
    </div>
</form>
@endsection