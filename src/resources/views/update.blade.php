@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/update.css') }}" />
@endsection

@section('content')
<form class="update-box" action="{{ empty($user->address_id) ? route('profile.store'):route('profile.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <h2>プロフィール設定</h2>
    <div class="update-mydata">
        <img id="preview" class="profile-image" src="{{ asset('storage/' . ($user->img_url ??'profile.png')) }}">
        <button type="button" class="image-btn" onclick="document.getElementById('image-input').click();">画像を選択する</button>
        <input type="file" id="image-input" name="image" accept="image/*" style="display: none;" onchange="previewImage(this);">
    </div>
    <div class="update-input-box">
        <ul>
            <li>ユーザー名</li>
            <li>
                <input type="text" name="name" value="{{ old('name',$user->name) }}">
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
            <li>郵便番号</li>
            <li>
                <input type="text" name="code" value="{{ old('code',$user->address->code ?? '') }}">
            </li>
            <li>
                <div class="form-error">
                    @error('code')
                    <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>
            </li>
        </ul>
        <ul>
            <li>住所</li>
            <li>
                <input type="text" name="address" value="{{ old('address',$user->address->address ?? '') }}">
            </li>
            <li>
                <div class="form-error">
                    @error('address')
                    <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>
            </li>
        </ul>
        <ul>
            <li>建物名</li>
            <li>
                <input type="text" name="building" value="{{ old('building',$user->address->building ?? '') }}">
            </li>
            <li>
                <div class="form-error">
                    @error('building')
                    <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>
            </li>
        </ul>
    </div>
    <div class="update-form">
        <button type="submit" class="update-btn">更新する</button>
    </div>
</form>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection