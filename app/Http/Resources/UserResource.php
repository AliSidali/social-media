<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $notifications = $this->receivedNotifications;
        $notReadNotifications = $this->getNotReadNotifications($notifications);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "email_verified_at" => $this->email_verified_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "username" => $this->username,
            "cover_path" => $this->cover_path ? Storage::url($this->cover_path) : null,
            "avatar_path" => $this->avatar_path ? Storage::url($this->avatar_path) : null,
            "notifications" => NotificationResource::collection($notifications),
            "notReadNotificationNum" => count($notReadNotifications),
            "role" => $this->pivot?->role,
        ];
    }

    protected function getNotReadNotifications($notifications)
    {
        $notReadNotifications = [];
        foreach ($notifications as $notification) {
            if (!$notification->is_read) {
                $notReadNotifications[] = $notification;
            }
        }
        return $notReadNotifications;
    }
}
