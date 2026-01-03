<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
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
            'slug' => $this->slug,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'image_url' => $this->image_url,
            'image_description' => $this->image_description,
            'is_visible' => $this->is_visible,
            'is_slider' => $this->is_slider,
            'is_featured' => $this->is_featured,
            'is_breaking' => $this->is_breaking,
            'is_recommended' => $this->is_recommended,
            'registered_only' => $this->registered_only,
            'is_paid_only' => $this->is_paid_only,
            'publish_status' => $this->publish_status,
            'publish_date' => $this->publish_date?->toISOString(),
            'show_author' => $this->show_author,
            'average_rating' => (float) $this->average_rating,
            'rating_count' => $this->rating_count,
            'allow_print_pdf' => $this->allow_print_pdf,
            'translations' => $this->whenLoaded('translations', function () {
                return $this->translations->map(function ($translation) {
                    return [
                        'language_id' => $translation->language_id,
                        'title' => $translation->title,
                        'summary' => $translation->summary,
                        'content' => $translation->content,
                    ];
                });
            }),
            'category' => new BlogCategoryResource($this->whenLoaded('category')),
            'author' => $this->when($this->show_author && $this->relationLoaded('user'), function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'avatar' => $this->user->profile_picture ? asset('storage/' . $this->user->profile_picture) : null,
                ];
            }),
            'tags' => BlogTagResource::collection($this->whenLoaded('tags')),
            'attachments' => $this->whenLoaded('attachments', function () {
                return $this->attachments->map(function ($attachment) {
                    return [
                        'id' => $attachment->id,
                        'file_path' => asset('storage/' . $attachment->file_path),
                        'file_name' => $attachment->file_name,
                        'file_type' => $attachment->file_type,
                    ];
                });
            }),
            'references' => $this->whenLoaded('references', function () {
                return $this->references->map(function ($reference) {
                    return [
                        'id' => $reference->id,
                        'title' => $reference->title,
                        'url' => $reference->url,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
