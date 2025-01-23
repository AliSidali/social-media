<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user_id = auth()->user()?->id;
        $pivotData = $this->users()->wherePivot('user_id', $user_id)->first()?->pivot;
        return [
            "id" => $this->id,
            "name" => $this->name,
            "slug" => $this->slug,
            "autoApproval" => $this->auto_approval,
            "cover_path" => $this->cover_path ? Storage::url($this->cover_path) : null,
            "thumbnail_path" => $this->thumbnail_path ? Storage::url($this->thumbnail_path) : '/imgs/no_thumbnail.png',
            "status" => $pivotData?->status,
            "role" => $pivotData?->role,
            "about" => $this->about,
            "description" => Str::words($this->about, 7, ' '),
        ];
    }
}
