<?php

namespace Tests\Unit;

use App\DTOs\WalletDTO;
use App\Models\User;
use App\Repositories\EloquentWalletRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentWalletRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentWalletRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(EloquentWalletRepository::class);
    }

    public function testGetById(): void
    {
        $user = User::factory()->create();

        $wallet = $this->repository->getById($user->wallet->id);

        $this->assertNotNull($wallet);
        $this->assertInstanceOf(WalletDTO::class, $wallet);
        $this->assertEquals($user->wallet->balance, $wallet->balance, "expected '{$user->wallet->balance}' got '{$wallet->balance}'");
    }

    public function testGetByIdNotFound(): void
    {
        $wallet = $this->repository->getById(2);

        $this->assertNull($wallet);
    }

    public function testGetByUserId(): void
    {
        $user = User::factory()->create();

        $wallet = $this->repository->getByUserId($user->id);

        $this->assertNotNull($wallet);
        $this->assertInstanceOf(WalletDTO::class, $wallet);
        $this->assertEquals($user->wallet->balance, $wallet->balance, "expected '{$user->wallet->balance}' got '{$wallet->balance}'");
    }

    public function testGetByUserIdNotFound(): void
    {
        $wallet = $this->repository->getByUserId(2);

        $this->assertNull($wallet);
    }
}
