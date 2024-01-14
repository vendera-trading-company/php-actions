<?php

use Illuminate\Support\Facades\Route;
use Tests\Helpers\ActionLogin;
use VenderaTradingCompany\PHPActions\Action;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/login', function () {
    $response = Action::run(ActionLogin::class);

    if (empty($response->getData('result'))) {
        return response()->json([
            'logged_in' => false,
            'session_id' => $response->getData('session_id')
        ]);
    }

    return response()->json([
        'logged_in' => true,
        'session_id' => $response->getData('session_id')
    ]);
})->name('login');
