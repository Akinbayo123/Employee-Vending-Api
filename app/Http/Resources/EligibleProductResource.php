<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EligibleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'employee' => [
                'name' => $this->full_name,
                'card_number' => $this->card_number,
            ],
            'quotas' => [
                'juice' => $this->classification->daily_juice_limit ?? 0,
                'meal' => $this->classification->daily_meal_limit ?? 0,
                'snack' => $this->classification->daily_snack_limit ?? 0
            ]
        ];
    }
}
