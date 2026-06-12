@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}" />
@endsection

@section('content')
<form class="address_box" action="{{ route('update.address',['item_id' =>$item_id]) }}" method="post">
    @csrf
    <h1>住所の変更</h1>
    <div class="address-input">
        <ul>
            <li>郵便番号</li>
            <li>
                <input type="text" name="code" class="input-line" value="{{ old('code',Auth::user()->address->code ??'')}}">
            </li>
        </ul>
        <ul>
            <li>住所</li>
            <li>
                <input type="text" name="address" class="input-line" value="{{ old('address',Auth::user()->address->address ??'') }}">
            </li>
        </ul>
        <ul>
            <li>建物名</li>
            <li>
                <input type="text" name="building" class="input-line" value="{{ old('building',Auth::user()->address->building ??'') }}">
            </li>
        </ul>
    </div>
    <div class="update-form">
        <button type="submit" class="update-btn">更新する</button>
    </div>
</form>
@endsection
