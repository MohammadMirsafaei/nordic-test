<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddMoneyTest extends TestCase
{
    use RefreshDatabase;

    public function testAddMoney(): void
    {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/add-money', [
            'user_id' => $user->id,
            'amount' => 3000,
        ]);

        $transaction = $user->wallet->transactions[0];

        $response->assertStatus(200);
        $response->assertJson([
            'reference_id' => "CRED{$transaction->created_at->format('YmdHisv')}",
        ]);
    }
}
