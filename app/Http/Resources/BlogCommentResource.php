<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCommentResource extends JsonResource
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
            'blog_post_id' => $this->blog_post_id,
            'user' => $this->whenLoaded('commentable', function () {
                $commenter = $this->commentable;

                if (! $commenter) {
                    return null;
                }

                return [
                    'id' => $commenter->id ?? null,
                    'name' => $commenter->name ?? null,
                    'avatar' => isset($commenter->profile_picture) && $commenter->profile_picture
                        ? asset('storage/' . $commenter->profile_picture)
                        : null,
                    'type' => class_basename($commenter),
                ];
            }),
            'comment' => $this->content,
            'is_approved' => $this->is_approved,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
