<?php

namespace App\Telegram\Bot\Requests\Message\Helpers;

/**
 * Вспомогательный класс для генерации кнопок в ответе бота
 */
class Buttons
{
    public static array $buttons = [
        'inline_keyboard' => []
    ];

    public static function make(string $text, array $data = []): void
    {
        Buttons::$buttons['inline_keyboard'][][] = [
            'text' => $text,
            'callback_data' => json_encode($data),
        ];;
    }
}
