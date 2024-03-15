<?php

namespace App\Telegram\Bot\Requests;

/**
 * Класс для формирования запроса к боту
 *
 */
class TelegramRequest
{
    protected array $payload = [];
    protected string $method;

    /**
     * Получаем тело запроса
     *
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * Устанавливаем тело запроса
     *
     * @param array $payload
     * @return $this
     */
    public function setPayload(array $payload): TelegramRequest
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Устанавливаем API endpoint
     *
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method): TelegramRequest
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Получаем API endpoint
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

}
