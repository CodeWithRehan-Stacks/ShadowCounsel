<?php

use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/subscribe', [SubscriptionController::class, 'store'])->name('subscribe.store');
