<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdMobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'app_id' => $this->app_id,
            'banner_id' => $this->banner_id,
            'interstitial_id' => $this->interstitial_id,
            'rewarded_id' => $this->rewarded_id,
            'native_id' => $this->native_id,
            'is_live' => $this->is_live,
        ];
    }
}
