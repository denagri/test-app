<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_registration_and_email_verification_flow(): void
    {
        Notification::fake();

        $registrationData = [
            'name'                  => 'テストユーザー',
            'email'                 => 'test_unique_email@example.com',
            'password'              => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
        ];

        $response = $this->post(route('register.step1.post'), $registrationData);
        if (session('errors')) {
            dump("--- バリデーションエラーが発生しました ---");
            dd(session('errors')->getBag('default')->getMessages());
        }

        $response->assertRedirect(route('verification.notice'));
        $this->assertDatabaseHas('users', [
            'email' => 'test_unique_email@example.com',
            'email_verified_at' => null,
        ]);
        $user = User::where('email', 'test_unique_email@example.com')->first();
        Notification::assertSentTo($user, VerifyEmail::class);

        $verifyPageResponse = $this->actingAs($user)->get(route('verification.notice'));
        $verifyPageResponse->assertStatus(200);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $verificationResponse = $this->actingAs($user)->get($verificationUrl);
        $verificationResponse->assertRedirect(route('register.step2'));
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);

        $profilePageResponse = $this->actingAs($user)->get(route('register.step2'));
        $profilePageResponse->assertStatus(200);
    }
}