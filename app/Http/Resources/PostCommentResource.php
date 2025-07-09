<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = auth()->user();
        return [
            'id' => $this->id,
            'text' => $this->text,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user_has_comment_reaction' => $this->reactions->where('user_id', $user->id)->count(),
            'comment_reaction_num' => $this->reactions->count(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->name,
                'avatar_path' => Storage::url($this->user->avatar_path),
            ],
            'sub_comments' => $this->childComments,
            'subcomment_num' => $this->numOfComments,
            'attachment' => new AttachmentResource($this->attachment) ?? null
        ];
    }
}
