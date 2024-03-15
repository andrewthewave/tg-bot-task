<?php

namespace App\Telegram\Bot;

use App\Models\Click;
use App\Telegram\Bot\Requests\Message\Helpers\Buttons;
use App\Telegram\Bot\Requests\Message\Message;
use App\Telegram\Bot\Requests\TelegramRequest;
use Illuminate\Support\Facades\Log;

/**
 * Класс-обработчик запросов от вебхука, для потенциального расширения функционала
 */
class WebhookHandler
{
    /**
     * Массив обработчиков запросов вебхука
     * @var callable[]
     */
    private array $handlers = [];

    /**
     * Добавляем обработчик запросов
     * @param callable $handler
     * @return WebhookHandler
     */
    public function addHandler(callable $handler): WebhookHandler
    {
        $this->handlers[] = $handler;
        return $this;
    }

    /**
     * Обрабатываем входящий запрос
     * Возвращает объект реквеста в результате обработки, либо ошибку, если обработчик не найден
     *
     * @param array $payload
     * @return TelegramRequest
     * @throws \Exception
     */
    public function handle(array $payload): TelegramRequest
    {
        $this
            ->addHandler([$this, 'messageHandler'])
            ->addHandler([$this, 'queryHandler']);
        foreach ($this->handlers as $handler) {
            /** @var TelegramRequest $request */
            $request = call_user_func($handler, $payload);
            if (!empty($request->getPayload())) {
                return $request;
            }
        }
        throw new \Exception('Не задан необходимый обработчик');
    }


    /**
     * Обработка входящего сообщения
     *
     * @param array $payload
     * @return Message
     */
    public function messageHandler(array $payload): Message
    {
        $message = new Message();
        if (!isset($payload['message'])) {
            return $message;
        }
        $id = $payload['message']['from']['id'];
        $text = "Ваш ID Telegram: $id\. " . $payload['message']['text'];
        if (isset($payload['message']['entities'][0]['type'])) {
            if ($payload['message']['entities'][0]['type'] === 'bot_command') {
                $text = 'Отправь мне сообщение, чтобы узнать свой ID\!';
            }
        } else {
            Buttons::make('Сколько раз нажали кнопку?');
        }
        $message->setMessage(
            $id,
            $text,
            Buttons::$buttons,
        );
        return $message;
    }

    /**
     * Обработка нажатия кнопки
     *
     * @param array $payload
     * @return Message
     */
    public function queryHandler(array $payload): Message
    {
        $message = new Message();
        if (!isset($payload['callback_query'])) {
            return $message;
        }
        $click = Click::query()->firstOrNew(
            ['id' => 1],
            ['count' => 0],
        );
        $id = $payload['callback_query']['from']['id'];
        $click->count += 1;
        $text = "Количество нажатий кнопки: " . $click->count;
        $click->save();
        Buttons::make('Сколько раз нажали кнопку?');
        $message->setMessage(
            $id,
            $text,
            Buttons::$buttons,
        );
        return $message;
    }
}
