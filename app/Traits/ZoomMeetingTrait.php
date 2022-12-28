<?php

namespace App\Traits;

use DateTime;
use Exception;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use JsonException;

/**
 * Class ZoomMeetingTrait
 *
 * @package namespace App\Traits
 */
trait ZoomMeetingTrait
{
    /**
     * @var Client
     */
    public Client $client;

    /**
     * @var string
     */
    public string $jwt;

    /**
     * @var string[]
     */
    public array $headers;

    /**
     *
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->jwt = $this->generateZoomToken();
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->jwt,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * @return string
     */
    public function generateZoomToken(): string
    {
        $key = env('ZOOM_API_KEY', '');
        $secret = env('ZOOM_API_SECRET', '');
        $payload = [
            'iss' => $key,
            'exp' => strtotime('+1 minute'),
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    /**
     * @return mixed
     */
    private function retrieveZoomUrl(): mixed
    {
        return env('ZOOM_API_URL', '');
    }

    /**
     * @param string $dateTime
     *
     * @return string
     */
    public function toZoomTimeFormat(string $dateTime): string
    {
        try {
            $date = new DateTime($dateTime);

            return $date->format('Y-m-d\TH:i:s');
        } catch (Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : ' . $e->getMessage());

            return '';
        }
    }

    /**
     * @param $data
     *
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function create($data): array
    {
        $path = 'users/me/meetings';
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->headers,
            'body' => json_encode([
                'topic' => $data['topic'],
                'type' => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'duration' => $data['duration'],
                'agenda' => (!empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone' => 'Asia/Kolkata',
                'settings' => [
                    'host_video' => $data['host_video'] == "1",
                    'participant_video' => $data['participant_video'] == "1",
                    'waiting_room' => true,
                ],
            ], JSON_THROW_ON_ERROR),
        ];

        $response = $this->client->post($url . $path, $body);

        return [
            'success' => $response->getStatusCode() === 201,
            'data' => json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR),
        ];
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function update($id, $data): array
    {
        $path = 'meetings/' . $id;
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->headers,
            'body' => json_encode([
                'topic' => $data['topic'],
                'type' => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'duration' => $data['duration'],
                'agenda' => (!empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone' => 'Asia/Kolkata',
                'settings' => [
                    'host_video' => $data['host_video'] == "1",
                    'participant_video' => $data['participant_video'] == "1",
                    'waiting_room' => true,
                ],
            ], JSON_THROW_ON_ERROR),
        ];
        $response = $this->client->patch($url . $path, $body);

        return [
            'success' => $response->getStatusCode() === 204,
            'data' => json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR),
        ];
    }

    /**
     * @param $id
     *
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function get($id): array
    {
        $path = 'meetings/' . $id;
        $url = $this->retrieveZoomUrl();
        $this->jwt = $this->generateZoomToken();
        $body = [
            'headers' => $this->headers,
            'body' => json_encode([], JSON_THROW_ON_ERROR),
        ];

        $response = $this->client->get($url . $path, $body);

        return [
            'success' => $response->getStatusCode() === 204,
            'data' => json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR),
        ];
    }

    /**
     * @param string $id
     *
     * @return bool[]
     * @throws GuzzleException
     */
    public function delete(string $id): array
    {
        $path = 'meetings/' . $id;
        $url = $this->retrieveZoomUrl();
        $body = [
            'headers' => $this->headers,
            'body' => json_encode([]),
        ];

        $response = $this->client->delete($url . $path, $body);

        return [
            'success' => $response->getStatusCode() === 204,
        ];
    }
}
