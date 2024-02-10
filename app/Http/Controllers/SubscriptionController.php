<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    /** @var User $user */
    $user = auth()->user();
    return view('subscription', [
      'intent' => $user->createSetupIntent()
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      $request->user()->newSubscription(
        $request->plan,
        $request->price_id
      )->create($request->payment_method);

      return redirect()->route('subscription.success');
    } catch (IncompletePayment $exception) {
      return redirect()->route(
        'cashier.payment',
        [$exception->payment->id, 'redirect' => route('home')]
      );
    }

    // return $request->user()
    //   ->newSubscription(request('plan'), request('price_id'))
    //   // ->trialDays(5)
    //   ->allowPromotionCodes()
    //   ->checkout([
    //     'success_url' => route('subscription.success'),
    //     'cancel_url' => route('subscription.cancelled'),
    //     'metadata' => [
    //       'plan' => request('plan'),
    //       'price_id' => request('price_id')
    //     ],
    //   ]);
  }

  public function success()
  {
    dd('Subscription successful');
  }

  public function cancel()
  {
    dd('Subscription canceled');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
