<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function test_registration(): void
    {
        $email = $this->faker->email;
        $payload = [
            'email' => $email,
            'password' => $this->faker->password(10),
        ];

        $response = $this->json('post', 'api/register', $payload, ['Accept' => 'application/json']);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    public function test_login(): void
    {
        $password = $this->faker->password(10);
        $user = User::factory()->create(['password' => $password]);

        $payload = [
            'email' => $user->email,
            'password' => $password
        ];

        $response = $this->json('post', 'api/login', $payload, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_OK);

        $data = (array)json_decode($response->content(), true);

        $this->assertTrue($data['status']);
        $this->assertArrayHasKey('token', $data);
    }

    public function test_exchange_rate(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('get', 'api/cryptocurrency/rates', ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_OK);

        $data = (array)json_decode($response->content(), true);

        $this->assertTrue($data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('rates', $data['data']);
    }
}
