<?php

namespace Tests\Feature\Api\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_user(): void
    {
        $fakeData = User::factory()->make();

        $userData = [
            'name' => $fakeData->name,
            'email' => $fakeData->email,
            'password' => $fakeData->password,
            'password_confirmation' => $fakeData->password
        ];

        $response = $this->postJson(route('api.v1.auth.register'),  $userData);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson(['message' => 'You success registered!']);
    }

    public function test_can_login_user(): void
    {
        $password = 'password';

        $fakeData = User::factory()->create([
            'password' => $password
        ]);

        $userData = [
            'email' => $fakeData->email,
            'password' => $password,
        ];

        $response = $this->postJson(route('api.v1.auth.login'),  $userData);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'user' => [
                'name' => $fakeData->name,
                'email' => $fakeData->email
            ]
        ]);
    }

    public function test_can_request_reset_password(): void
    {
        $fakeData = User::factory()->create();

        $userData = [
            'email' => $fakeData->email,
        ];

        $response = $this->putJson(route('api.v1.auth.requestResetPassword'),  $userData);
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson(['message' => 'If user with this email exists we send email!']);
    }

    public function test_can_reset_password(): void
    {
        $fakeData = User::factory()->withVerifiedToken()->create();

        $userData = [
            'email' => $fakeData->email,
            'token' => $fakeData->verified_token['value'],
            'password' => $fakeData->password,
            'password_confirmation' => $fakeData->password
        ];

        $response = $this->putJson(route('api.v1.auth.resetPassword'),  $userData);
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson(['message' => 'Password success changed!']);
    }

    public function test_can_request_email_verification(): void
    {
        $fakeData = User::factory()->create();

        $userData = [
            'email' => $fakeData->email,
        ];

        $response = $this->putJson(route('api.v1.auth.requestEmailVerification'),  $userData);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'If user with this email exists we send email!'
        ]);
    }

    public function test_can_verify_email(): void
    {
        $fakeData = User::factory()->withVerifiedToken()->create();

        $userData = [
            'email' => $fakeData->email,
            'token' => $fakeData->verified_token['value']
        ];

        $response = $this->get(route('api.v1.auth.verifyEmail', $userData));
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson(['message' => 'Email success verified!']);
    }
}
