<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // Basic Information
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            
            // Contact Information
            'mobile' => $this->mobile,
            'whatsapp_number' => $this->whatsapp_number,
            'mobile_verified_at' => $this->mobile_verified_at?->toISOString(),
            
            // Personal Details
            'dob' => $this->dob,
            'gender' => $this->gender,
            'bio' => $this->bio,
            'short_bio' => $this->short_bio,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'is_differently_abled' => $this->is_differently_abled,

            // Address Information
            'locality' => $this->locality,
            'address' => $this->address,
            'pincode' => $this->pincode,
            'aadhaar_number' => $this->aadhaar_number,
            'document' => $this->document ? asset('storage/' . $this->document) : null,
            'country_id' => $this->country_id,
            'country' => $this->whenLoaded('country', fn() => [
                'id' => $this->country->id,
                'name' => $this->country->name,
            ]),
            'state_id' => $this->state_id,
            'state' => $this->whenLoaded('state', fn() => [
                'id' => $this->state->id,
                'name' => $this->state->name,
            ]),
            'city_id' => $this->city_id,
            'city' => $this->whenLoaded('city', fn() => [
                'id' => $this->city->id,
                'name' => $this->city->name,
            ]),

            // Family Details
            'fathers_name' => $this->fathers_name,
            'mothers_name' => $this->mothers_name,
            'parent_mobile_number' => $this->parent_mobile_number,

            // Preferences
            'language_id' => $this->language_id,
            'timezone_id' => $this->timezone_id,
            'currency_id' => $this->currency_id,
            'dateformat_id' => $this->dateformat_id,
            'medium_of_exam' => $this->medium_of_exam,
            'favorite_topics' => $this->favorite_topics,
            'dark_mode_enabled' => $this->dark_mode_enabled,

            // Classifications & Social Groups
            'user_classifications_id' => $this->user_classifications_id,
            'community_id' => $this->community_id,
            'd_a_category_id' => $this->d_a_category_id,
            'religion_id' => $this->religion_id,
            'special_category_id' => $this->special_category_id,

            // Social Profiles
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'linkedin' => $this->linkedin,

            // Referral & Payout
            'referral' => $this->referral,
            'subscribe' => $this->subscribe,
            'payout' => $this->payout,
            'payout_email' => $this->payout_email,
            'payout_icon' => $this->payout_icon,
            'special_commission' => $this->special_commission,

            // Social Login
            'provider' => $this->provider,
            'provider_id' => $this->provider_id,

            // Security & Status
            'is_active' => $this->is_active,
            'is_banned' => $this->is_banned,
            'login_attempts' => $this->login_attempts,
            'account_locked_until' => $this->account_locked_until?->toISOString(),
            'account_locked_reason' => $this->account_locked_reason,
            'last_profile_update_at' => $this->last_profile_update_at?->toISOString(),
            'last_password_change_at' => $this->last_password_change_at?->toISOString(),

            // Account Deletion Request
            'delete_request_at' => $this->delete_request_at?->toISOString(),
            'delete_request_reason' => $this->delete_request_reason,

            // Education and Skills (relationships)
            'education' => $this->whenLoaded('education'),
            'skills' => $this->whenLoaded('skills'),

            // Timestamps
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
