<?php

namespace App\Http\Middleware;

use App\Http\Resources\NotificationResource;
use Inertia\Middleware;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\StorePostRequest;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = auth()->user();
        if (!$user) {
            return [...parent::share($request)];
        }
        $userWithNotifications = $user->load('receivedNotifications');
        $notReadNotifications = $user->getNotReadNotifications($userWithNotifications->receivedNotifications);
        return [
            ...parent::share($request),
            'auth' => [
                'user' => new UserResource($user),
                'notificationsData' => [
                    'notifications' => NotificationResource::collection($userWithNotifications->receivedNotifications),
                    'notReadNotificationNum' => count($notReadNotifications),
                ],
            ],
            'extensions' => StorePostRequest::$extensions,



        ];
    }
}
