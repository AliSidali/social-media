<?php

namespace App\Http\Resources;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $this->notificationPath();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'created_at' => $this->fromTimestampToTimeUnits($this->created_at),
            'created_by' => [
                'id' => $this->notificable->id,
                'parameter' => $this->notificable->slug ?? $this->notificable->username,
                'thumbnail_path' => Storage::url($this->notificable->thumbnail_path ?? $this->notificable->avatar_path),
                'path' => $this->notificationPath()

            ]


        ];
    }
    protected function notificationPath()
    {
        $path = "";
        if ($this->notificable instanceof Group) {
            $path = route('group.profile', $this->notificable->slug);

        } elseif ($this->notificable instanceof Post) {
            $path = route('post.view', $this->notificable->id);
        } elseif ($this->notificable instanceof User) {
            $path = route('profile.index', $this->notificable->username);
        }
        return $path;
    }


}
