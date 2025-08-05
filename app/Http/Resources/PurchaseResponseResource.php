<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Purchase successful',
            'slot' => [
                'slot_id' => $this->id,
                'machine' => $this->vendingMachine->location ?? null,
                'category' => $this->category,
                'slot_number' => $this->slot_number,
                'price' => $this->price,
            ]
        ];
    }
}
