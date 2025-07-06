<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class FCMService
{
    public function sendNotification($deviceToken, $title, $body, $data = [])
    {
        $payload = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default',
            ],
            'data' => $data,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . env('FCM_SERVER_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);

        return $response->json();
    }
}
