<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetBalanceTest extends TestCase
{
    use RefreshDatabase;

    public function testGetBalance(): void
    {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/get-balance', [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'balance' => $user->wallet->balance,
        ]);
    }

    public function testGetBalanceNotFound(): void
    {
        $response = $this->json('post', '/api/get-balance', [
            'user_id' => 2,
        ]);

        $response->assertStatus(422);
    }

}
