<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecaptchaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'site_key' => $this->site_key,
            'is_enabled' => $this->is_enabled,
            'version' => $this->version,
            'v3_score_threshold' => $this->version === 'v3' ? (float) $this->v3_score_threshold : null,
            'captcha_for_login' => $this->captcha_for_login,
            'captcha_for_register' => $this->captcha_for_register,
            'captcha_for_contact' => $this->captcha_for_contact,
        ];
    }
}
