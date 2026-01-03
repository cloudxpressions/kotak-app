<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Try to decode JSON value, otherwise return as-is
        $value = $this->value;
        $decodedValue = json_decode($value, true);
        
        return [
            'key' => $this->key,
            'value' => $decodedValue !== null ? $decodedValue : $value,
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
