@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
@endsection

@section('content')
<div class="box">
    <div class="purchase-box">
        <div class="goods-box">
            <img src="{{ asset('storage/'.$product->image_path) }}" class="image" alt="画像">
            <div class="goods-info">
                <h1 class="goods-name">{{ $product->name }}</h1>
                <div class="goods-price">￥{{ number_format($product->price) }}</div>
            </div>
        </div>
        <form class="method-box" action="" method="get">
            @csrf
            <h2 class="method-title">支払い方法</h2>
            <select name="payment_id" id="payment_select" class="input-method">
                <option value=""disabled selected>選択してください</option>
                @foreach($payments as $payment)
                <option value="{{ $payment->id }}">{{ $payment->method }}</option>
                @endforeach
            </select>
            <div class="form-error">
                @error('payment_id')
                <div style="color:red;">{{ $message }}</div>
                @enderror
            </div>
        </form>
        <div class="address-box">
            <div class="address-title">
                <h2 class="title">配送先</h2>
                <a class="address-change" href="{{ route('edit.address',['item_id' =>$product->id]) }}">変更する</a>
            </div>
            <div class="address-info">
                @php
                    $sessionAddressId =session('shipping_address_' .$product->id);
                    $displayAddress =$sessionAddressId
                    ?\App\Models\Address::find($sessionAddressId)
                    :Auth::user()->address;
                @endphp

                @if(Auth::user()->address)
                    〒 {{ $displayAddress->code }}<br>
                    {{ $displayAddress->address }}<br>
                    {{ $displayAddress->building }}
                @else
                    <span style="color:red;">配送先が登録されていません</span>
                @endif
                <div class="form-error">
                    @error('address_check')
                    <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="confirmation-box">
        <table>
            <tr>
                <th>商品代金</th>
                <th>￥{{ number_format($product->price) }}</th>
            </tr>
            <tr>
                <th>支払い方法</th>
                <th id="display-payment">選択してください</th>
            </tr>
        </table>
        <form class="purchase-form" action="{{ route('purchase.store',['item_id'=>$product->id]) }}" method="post">
            @csrf
            <input type="hidden" name="payment_id" id="hidden_payment_id">
            <button type="submit" class="purchase-btn">購入する</button>
        </form>
    </div>
</div>
<script>
    document.getElementById('payment_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('display-payment').textContent = selectedOption.text;
        document.getElementById('hidden_payment_id').value =this.value;
    });
</script>
@endsection