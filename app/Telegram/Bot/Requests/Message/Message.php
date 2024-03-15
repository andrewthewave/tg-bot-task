<?php

namespace App\Telegram\Bot\Requests\Message;

use App\Telegram\Bot\Requests\TelegramRequest;

class Message extends TelegramRequest
{
    /**
     * Формирует тело сообщения для отправки
     *
     * @param mixed $id
     * @param string $msg
     * @param array $buttons
     * @param string $parseMode
     * @return void
     */
    public function setMessage(
        mixed  $id,
        string $msg,
        array  $buttons = [],
        string $parseMode = 'MarkdownV2'
    ): void
    {
        $this->setMethod('sendMessage');
        $payload = [
            'chat_id' => $id,
            'text' => $msg,
            'parse_mode' => $parseMode,
        ];
        if (!empty($buttons)) {
            $payload['reply_markup'] = $buttons;
        }
        $this->setPayload($payload);
    }
}
