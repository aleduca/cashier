<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <form action="{{route('subscription.store')}}" method="POST">
            @csrf
            <input type="text" name="plan" value="premium">
            <input type="hidden" name="price_id" value="price_1OhX6dGz3EyK1lQ9Z2xidaQl" />
            <button id="checkout-and-portal-button" type="submit">Checkout</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
