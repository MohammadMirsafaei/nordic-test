<?php

namespace App\Http\Resources;

use App\DTOs\WalletDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance' => $this->balance,
        ];
    }
}
