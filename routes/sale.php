<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Cashier;

Route::get('/buy/product', function () {
  return view('buy-product');
})->middleware('auth')->name('buy-product');

Route::get('/subscription', function (Request $request) {
  return view('subscription');
})->middleware('auth')->name('subscription');

Route::get('/checkout', function (Request $request) {
  $productId = 'price_1OcGvjGz3EyK1lQ9GsQyvsMQ';
  return $request->user()
    ->allowPromotionCodes()
    ->checkout([$productId => 2], [
      'success_url' => route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
      'cancel_url' => route('checkout-cancel'),
      'metadata' => ['product_id' => $productId, 'user_id' => auth()->id()],
    ]);
})->middleware('auth')->name('checkout');

Route::get('/checkout/success', function (Request $request) {
  $sessionId = $request->get('session_id');

  $metadata = Cashier::stripe()->checkout->sessions->retrieve($sessionId);

  // Log::info($metadata);
})->middleware('auth')->name('checkout-success');

Route::get('/checkout/cancel', function (Request $request) {
  return view('checkout-cancel');
})->middleware('auth')->name('checkout-cancel');
