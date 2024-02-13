<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class InvoiceController extends Controller
{

  public function index()
  {
    /** @var User $user */
    $user = auth()->user();

    $invoices = $user->invoices();

    $upcoming = $user->subscription('premium')->upcomingInvoice();

    return view('invoices', [
      'invoices' => $invoices,
      'upcoming' => $upcoming,
    ]);
  }

  public function create()
  {
    /** @var User $user */
    $user = auth()->user();

    return view('invoice', [
      'intent' => $user->createSetupIntent()
    ]);
  }

  public function store()
  {
    // dd(request()->all());
    try {
      /** @var User $user */
      $user = auth()->user();

      $user->createOrGetStripeCustomer();
      $user->updateDefaultPaymentMethod(request()->payment_method);

      $user->invoicePrice(request()->price_id, 5);
    } catch (IncompletePayment $exception) {
      if ($exception->payment->status === 'requires_action' && $exception->payment->next_action->type === 'boleto_display_details') {
        dd($exception->payment->next_action->boleto_display_details->pdf);
      }
    }
  }

  public function invoice($id, Request $request)
  {
    return $request->user()->downloadInvoice($id, [], 'my-invoice');
  }
}
