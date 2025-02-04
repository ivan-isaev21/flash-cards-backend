<?php

namespace Tests\Feature\Api\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class MeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_change_password()
    {
        $fakeData = User::factory()->withVerifiedToken()->create();

        $userData = [
            'email' => $fakeData->email,
            'password' => $fakeData->password,
            'password_confirmation' => $fakeData->password
        ];

        $response = $this->actingAs($fakeData)->putJson(route('api.v1.me.changePassword'),  $userData);
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson(['message' => 'Password success changed!']);
    }

    public function test_can_update()
    {
        $fakeData = User::factory()->withVerifiedToken()->create();

        $userData = [
            'name' => $fakeData->name,
            'email' => $fakeData->email,
        ];

        $response = $this->actingAs($fakeData)->putJson(route('api.v1.me.update'),  $userData);
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson($userData);
    }

    public function test_show()
    {
        $fakeData = User::factory()->withVerifiedToken()->create();
        $response = $this->actingAs($fakeData)->getJson(route('api.v1.me.show'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['id' => $fakeData->id]);
    }
}
