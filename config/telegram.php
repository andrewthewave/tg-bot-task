<?php
// Необходимая конфигурация для работы бота
return [
    'callback_uri' => env('TELEGRAM_CALLBACK_URI'),
    'api_url' => env('TELEGRAM_API_URL', 'https://api.telegram.org/bot'),
    'api_token' => env('TELEGRAM_API_TOKEN'),
];
