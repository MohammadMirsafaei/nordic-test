<?php

namespace App\Http\Controllers;

use App\Exceptions\WalletNotFoundException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\GetBalanceRequest;
use App\Http\Resources\GetBalanceResource;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    public function __construct(
        private WalletService $walletService,
    )
    {
    }

    public function getBalance(GetBalanceRequest $request): JsonResponse
    {
        try {

            $wallet = $this->walletService->getWalletByUserId($request->user_id);
            return response()->json(new GetBalanceResource($wallet));

        } catch (WalletNotFoundException $ex) {
            Log::alert(
                "Wallet existance validation bypassed in request but exception caught in WalletService: {$ex->getMessage()}",
                ['exception' => $ex]
            );
            return ResponseHelper::notFoundError("Wallet not found");
        }
        catch (\Throwable $th) {
            Log::error(
                "Unhandled expection in getting balance for user: {$request->user_id}",
                ['exception' => $th]
            );
            return ResponseHelper::serverInternalError();
        }
    }
}
