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

          <div id="messages"></div>
          <form action="{{route('subscription.store')}}" method="post" id="payment-form">
            @csrf
            <input type="hidden" name="plan" value="premium">
            <input type="hidden" name="price_id" value="price_1OhX6dGz3EyK1lQ9Z2xidaQl" />
            <input id="card-holder-name" type="text" placeholder="Your Name in the credit card">

            <!-- Stripe Elements Placeholder -->
            <div id="card-element"></div>

            <button id="card-button" class="align-middle select-none font-sans font-bold text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 px-6 rounded-lg bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none mt-3" data-secret="{{ $intent->client_secret }}">
              Pay
            </button>
          </form>


          {{-- <form action="{{route('subscription.store')}}" method="POST">
          @csrf
          <input type="text" name="plan" value="premium">
          <input type="hidden" name="price_id" value="price_1OhX6dGz3EyK1lQ9Z2xidaQl" />
          <button id="checkout-and-portal-button" type="submit">Checkout</button>
          </form> --}}
        </div>
      </div>
    </div>
  </div>
  @push('scripts')
  <script src="https://js.stripe.com/v3/"></script>

  <script>
    const stripe = Stripe('pk_test_51HG9eAGz3EyK1lQ9ld9Ora0Q4VCtiLIjVvCP3Y9mgGij4bjHfluVV4JaVZWHavWYiU2LMDo02vcJn3J3qUFTLiOL00zPcHpyiB');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', async (e) => {
      e.preventDefault();
      const {
        setupIntent
        , error
      } = await stripe.confirmCardSetup(
        clientSecret, {
          payment_method: {
            card: cardElement
            , billing_details: {
              name: cardHolderName.value
            }
          }
        }
      );

      if (error) {
        const messages = document.getElementById('messages');
        messages.style.color = "red";
        messages.style.fontSize = "18px";
        messages.textContent = error.message;

        setTimeout(() => {
          messages.textContent = '';
        }, 5000);
      } else {
        const form = document.getElementById('payment-form');
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'payment_method');
        hiddenInput.setAttribute('value', setupIntent.payment_method);
        form.appendChild(hiddenInput);
        form.submit();
      }
    });

  </script>
  @endpush
</x-app-layout>
