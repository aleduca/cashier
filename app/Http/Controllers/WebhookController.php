<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as ControllersWebhookController;

class WebhookController extends ControllersWebhookController
{
  // public function handlePaymentIntentSucceeded(array $payload)
  // {
  //   Log::info('PaymentIntentSucceeded ' . json_encode($payload));
  // }
}
