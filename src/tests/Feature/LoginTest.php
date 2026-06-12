<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_メールアドレスが未入力の場合にバリデーションエラーが発生する()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors(['email'=> 'メールアドレスを入力してください']);
    }
    public function test_パスワードが未入力の場合にバリデーションエラーが発生する()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);
        $response->assertSessionHasErrors(['password'=> 'パスワードを入力してください']);
    }
    public function test_登録されていない情報の場合にバリデーションエラーが発生する()
    {
        $response = $this->post('/login', [
            'email' => 'nofound@example.com',
            'password' => 'wrong-password',
        ]);
        $response->assertSessionHasErrors(['auth_failed'=> 'ログイン情報が登録されていません']);
        $this->assertGuest();
    }
    public function test_全ての項目が正しく入力されている場合ログインしてプロフィール画面へ遷移する()
    {
        $user =\App\Models\User::factory()->create([
            'email' =>'valid@example.com',
            'password' =>bcrypt('password123'),
        ]);
        $response = $this->post('/login', [
            'email' => 'valid@example.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }
    public function test_ログアウトができる()
    {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post('/logout');
    $this->assertGuest();
    $response->assertRedirect(route('login'));
    }
}
