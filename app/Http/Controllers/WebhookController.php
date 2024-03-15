<?php

namespace App\Http\Controllers;

use App\Telegram\Bot\Client;
use App\Telegram\Bot\Requests\Message\Message;
use App\Telegram\Bot\Requests\TelegramRequest;
use App\Telegram\Bot\WebhookHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    /**
     * Обработка вебхука
     *
     * @param Request $request
     * @param WebhookHandler $webhookHandler
     * @return mixed
     */
    public function __invoke(Request $request, WebhookHandler $webhookHandler): mixed
    {
        try {
            $telegramRequest = $webhookHandler->handle($request->all());
        } catch (\Exception $e) {
            $telegramRequest = (new Message());
            $telegramRequest->setMessage(
                id: env('TELEGRAM_OWNER_ID'),
                msg: 'Ошибка обработки запроса: ' . Str::replace('.', '\.', $e->getMessage()),
            );
        }
        $client = new Client($telegramRequest);
        return $client->run();

    }

    /**
     * Установка вебхука
     * @return void
     */
    public function setup()
    {
        $request = (new TelegramRequest())
            ->setMethod('setWebhook')
            ->setPayload([
                'url' => config('telegram.callback_uri'),
            ]);
        $client = new Client($request);
        $client->run();
    }
}
