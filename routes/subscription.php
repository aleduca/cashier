<?php

use App\Http\Controllers\PrivateController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Middleware\Subscribed;
use Illuminate\Support\Facades\Route;

Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
Route::post('/subscription/store', [SubscriptionController::class, 'store'])->name('subscription.store');
Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
Route::get('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancelled');
Route::get('/private', [PrivateController::class, 'index'])->name('private.index')->middleware([Subscribed::class . ':premium']);
