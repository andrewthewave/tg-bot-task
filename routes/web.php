<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::post('/callback', WebhookController::class)->withoutMiddleware([VerifyCsrfToken::class]);
Route::get('/webhook-setup', [WebhookController::class, 'setup']);
