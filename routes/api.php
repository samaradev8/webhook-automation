<?php

use App\Http\Controllers\WebhookController;
use App\Services\DealParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'webhook/v1'], function () {

    Route::post('/automation', [WebhookController::class, 'automation'])->name('automation');

//     Route::get('/logtest', function () {
//     Log::info('Test log triggered');
//     return 'Logged.';
// });

    // Route::post('/automation', function (Request $request, DealParserService $parser) {
    //     $dealName = $request->input('deal_name');

    //     try {
    //         $parsed = $parser->parse($dealName);
    //         return response()->json([
    //             'status' => 'parsed',
    //             'result' => $parsed,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], 400);
    //     }
    // });
});
