<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class SignupTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前が未入力の場合にバリデーションエラーが発生する()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }
    public function test_メールアドレスが未入力の場合にバリデーションエラーが発生する()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors(['email'=> 'メールアドレスを入力してください']);
    }
    public function test_パスワードが未入力の場合にバリデーションエラーが発生する()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors(['password'=> 'パスワードを入力してください']);
    }
    public function test_パスワードが7文字以下の場合にバリデーションエラーが発生する()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);
        $response->assertSessionHasErrors(['password'=> 'パスワードは8文字以上で入力してください']);
    }
    public function test_パスワードと確認用パスワードが一致しない場合にバリデーションエラーが発生する()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
        ]);
        $response->assertSessionHasErrors(['password'=> 'パスワードと一致しません']);
    }
    public function test_全ての項目が正しく入力されている場合会員登録してプロフィール画面へ遷移する()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'valid@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'valid@example.com',
        ]);
        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticated();
    }
}