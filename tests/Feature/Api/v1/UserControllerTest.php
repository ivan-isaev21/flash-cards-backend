<?php

namespace Tests\Feature\Api\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_user()
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
        $response->assertJson([
            'name' => $fakeData->name,
            'email' => $fakeData->email
        ]);
    }

    public function test_can_request_reset_password()
    {
        $fakeData = User::factory()->create();

        $userData = [
            'email' => $fakeData->email,
        ];

        $response = $this->putJson(route('api.v1.auth.requestResetPassword'),  $userData);
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson([
            'name' => $fakeData->name,
            'email' => $fakeData->email
        ]);
    }

    public function test_can_reset_password()
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
        $response->assertJson([
            'name' => $fakeData->name,
            'email' => $fakeData->email
        ]);
    }
}
