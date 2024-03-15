<?php

namespace App\Telegram\Bot;

use App\Telegram\Bot\Requests\TelegramRequest;
use Illuminate\Support\Facades\Http;

/**
 * Класс, непосредственно отправляющий заппросы к Telegram API
 */
class Client
{
    private TelegramRequest $request;
    private string $baseUrl;

    public function __construct(TelegramRequest $request)
    {
        $this->request = $request;
        $this->baseUrl = config('telegram.api_url', 'https://api.telegram.org/bot');
    }

    public function run(): mixed
    {
        return $this->prepareRequest();
    }

    /**
     * Подготовка запроса к отправке
     *
     * @return mixed
     */
    private function prepareRequest(): mixed
    {
        $endpoint = $this->baseUrl . config('telegram.api_token') . '/' . $this->request->getMethod();
        return $this->sendRequest($endpoint, $this->request->getPayload());
    }

    /**
     * Отправка запроса
     *
     * @param string $endpoint
     * @param array $payload
     * @return mixed
     */
    private function sendRequest(string $endpoint, array $payload = []): mixed
    {
        return Http::post($endpoint, $payload)->json();
    }
}
