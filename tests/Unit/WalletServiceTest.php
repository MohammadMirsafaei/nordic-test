<?php

namespace Tests\Unit;

use App\DTOs\TransactionDTO;
use App\DTOs\TransactionToCreateDTO;
use App\DTOs\WalletDTO;
use App\Enums\TransactionTypeEnum;
use App\Exceptions\WalletNotFoundException;
use App\Repositories\EloquentWalletRepository;
use App\Repositories\WalletRepositoryInterface;
use App\Services\WalletService;
use Carbon\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    private array $wallets;

    private array $transactions;

    private WalletService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->wallets = [
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

        $this->transactions = [];

        $this->app->bind(WalletRepositoryInterface::class, function() {
            return $this->mock(EloquentWalletRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('getById')->andReturnUsing(function ($id) {
                    return $this->wallets[$id] ?? null;
                });

                $mock->shouldReceive('getByUserId')->andReturnUsing(function ($id) {
                    return $this->wallets[$id] ?? null;
                });

                $mock->shouldReceive('updateBalanceByTransaction')
                ->andReturnUsing(function (TransactionToCreateDTO $transactionDTO, float $amount) {
                    $old = $this->wallets[$transactionDTO->userId];
                    $this->wallets[$transactionDTO->userId] = new WalletDTO(
                        $old->id,
                        $old->balance + $amount,
                        $old->userId,
                        $old->createdAt,
                        $old->updatedAt,
                    );

                    $this->transactions[] = new TransactionDTO(
                        1,
                        $amount > 0 ? $amount : $amount * -1,
                        TransactionTypeEnum::fromAmount($amount),
                        $amount > 0 ? 'CRED19648984' : 'DEB12124123',
                        $transactionDTO->userId,
                        now(),
                        now(),
                    );
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

    /**
     * @depends testGetWalletByUserId
     */
    public function testUpdateBalanceByUserId(): void
    {
        $this->service->updateBalanceByUserId(2, -200);
        $this->service->updateBalanceByUserId(2, -100);
        $this->service->updateBalanceByUserId(2, 100);

        $this->assertEquals(300.00, $this->service->getWalletByUserId(2)->balance);
        $this->assertCount(3, $this->transactions);
        $this->assertEquals(200, $this->transactions[0]->amount);
        $this->assertEquals(TransactionTypeEnum::DEBIT, $this->transactions[0]->type);
        $this->assertEquals(100, $this->transactions[1]->amount);
        $this->assertEquals(100, $this->transactions[2]->amount);
    }
}
