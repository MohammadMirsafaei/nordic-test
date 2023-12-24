<?php

namespace Tests\Unit;

use App\DTOs\WalletDTO;
use App\Exceptions\WalletNotFoundException;
use App\Repositories\EloquentWalletRepository;
use App\Repositories\WalletRepositoryInterface;
use App\Services\WalletService;
use Carbon\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    private array $data;

    private WalletService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = [
            1 => new WalletDTO(
                1,
                1000.00,
                1,
                Carbon::parse('2023-12-24 15:00:00'),
                Carbon::parse('2023-12-24 15:00:00'),
            ),
            2 => new WalletDTO(
                2,
                500.00,
                2,
                Carbon::parse('2023-11-24 15:00:00'),
                Carbon::parse('2023-11-24 15:00:00'),
            ),
            3 => new WalletDTO(
                3,
                100.50,
                3,
                Carbon::parse('2023-10-24 15:00:00'),
                Carbon::parse('2023-10-24 15:00:00'),
            ),
        ];

        $this->app->bind(WalletRepositoryInterface::class, function() {
            return $this->mock(EloquentWalletRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('getById')->andReturnUsing(function ($id) {
                    return $this->data[$id] ?? null;
                });

                $mock->shouldReceive('getByUserId')->andReturnUsing(function ($id) {
                    return $this->data[$id] ?? null;
                });
            });
        });

        $this->service = $this->app->make(WalletService::class);
    }

    public function testGetWalletById(): void
    {
        $wallet = $this->service->getWalletById(2);

        $this->assertNotNull($wallet);
        $this->assertInstanceOf(WalletDTO::class,$wallet);
        $this->assertEquals(500.00, $wallet->balance, "expected '500.00' got {$wallet->balance}");
    }

    public function testExceptionForNotFoundWallet(): void
    {
        $this->expectException(WalletNotFoundException::class);

        $this->service->getWalletById(5);
    }

    public function testGetWalletByUserId(): void
    {
        $wallet = $this->service->getWalletByUserId(2);

        $this->assertNotNull($wallet);
        $this->assertInstanceOf(WalletDTO::class,$wallet);
        $this->assertEquals(500.00, $wallet->balance, "expected '500.00' got {$wallet->balance}");
    }

    public function testExceptionForNotFoundWalletInGetByUserId(): void
    {
        $this->expectException(WalletNotFoundException::class);

        $this->service->getWalletByUserId(5);
    }


}
