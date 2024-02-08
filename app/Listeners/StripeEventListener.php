<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
  /**
   * Create the event listener.
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   */
  public function handle(WebhookReceived $event): void
  {
    if ($event->payload['type'] === 'payment_intent.succeeded') {
      Log::info('payment_intent.succeeded' . json_encode($event->payload));
    }

    if ($event->payload['type'] === 'checkout.session.completed') {
      Log::info('checkout.session.completed' . json_encode($event->payload));
    }

    if ($event->payload['type'] === 'customer.subscription.updated') {
      Log::info('customer.subscription.updated' . json_encode($event->payload));
    }

    if ($event->payload['type'] === 'customer.subscription.deleted') {
      Log::info('customer.subscription.deleted' . json_encode($event->payload));
    }

    if ($event->payload['type'] === 'checkout.session.async_payment_succeeded') {
      Log::info('PaymentIntentSucceededFromListener ' . json_encode($event->payload));
    }
  }
}
