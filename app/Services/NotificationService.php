<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\User;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {}

    public function send(
        User             $user,
        string           $title,
        string           $message,
        NotificationType $type = NotificationType::General,
    ): void {
        // Persist to DB
        $this->notificationRepository->create([
            'user_id' => $user->id,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
        ]);

        // Push via FCM if token exists
        if ($user->fcm_token) {
            $this->sendFcmPush($user->fcm_token, $title, $message, $type);
        }
    }

    public function sendBulk(iterable $users, string $title, string $message, NotificationType $type): void
    {
        foreach ($users as $user) {
            $this->send($user, $title, $message, $type);
        }
    }

    /**
     * Send Firebase Cloud Messaging push notification via HTTP v1 API.
     */
    private function sendFcmPush(
        string           $fcmToken,
        string           $title,
        string           $message,
        NotificationType $type,
    ): void {
        $projectId  = config('services.firebase.project_id');
        $accessToken = config('services.firebase.server_key');

        if (! $projectId || ! $accessToken) {
            return;
        }

        try {
            Http::withToken($accessToken)
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                    'message' => [
                        'token' => $fcmToken,
                        'notification' => [
                            'title' => $title,
                            'body'  => $message,
                        ],
                        'data' => [
                            'type' => $type->value,
                        ],
                    ],
                ]);
        } catch (\Throwable $e) {
            Log::warning('FCM push failed', [
                'token'   => substr($fcmToken, 0, 10) . '...',
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
