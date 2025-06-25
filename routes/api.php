<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'webhook/v1'], function () {

    Route::post('/automation', [WebhookController::class, 'automation'])->name('automation');
});