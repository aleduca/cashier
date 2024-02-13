<x-app-layout>

  @push('css')
  <style>
    form {
      width: 30vw;
      min-width: 500px;
      align-self: center;
      box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
        0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
      border-radius: 7px;
      padding: 40px;
    }

    .hidden {
      display: none;
    }

    #payment-message {
      color: rgb(105, 115, 134);
      font-size: 16px;
      line-height: 20px;
      padding-top: 12px;
      text-align: center;
    }

    #payment-element {
      margin-bottom: 24px;
    }

    /* Buttons and links */
    button {
      background: #5469d4;
      font-family: Arial, sans-serif;
      color: #ffffff;
      border-radius: 4px;
      border: 0;
      padding: 12px 16px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      display: block;
      transition: all 0.2s ease;
      box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
      width: 100%;
    }

    button:hover {
      filter: contrast(115%);
    }

    button:disabled {
      opacity: 0.5;
      cursor: default;
    }

    /* spinner/processing state, errors */
    .spinner,
    .spinner:before,
    .spinner:after {
      border-radius: 50%;
    }

    .spinner {
      color: #ffffff;
      font-size: 22px;
      text-indent: -99999px;
      margin: 0px auto;
      position: relative;
      width: 20px;
      height: 20px;
      box-shadow: inset 0 0 0 2px;
      -webkit-transform: translateZ(0);
      -ms-transform: translateZ(0);
      transform: translateZ(0);
    }

    .spinner:before,
    .spinner:after {
      position: absolute;
      content: "";
    }

    .spinner:before {
      width: 10.4px;
      height: 20.4px;
      background: #5469d4;
      border-radius: 20.4px 0 0 20.4px;
      top: -0.2px;
      left: -0.2px;
      -webkit-transform-origin: 10.4px 10.2px;
      transform-origin: 10.4px 10.2px;
      -webkit-animation: loading 2s infinite ease 1.5s;
      animation: loading 2s infinite ease 1.5s;
    }

    .spinner:after {
      width: 10.4px;
      height: 10.2px;
      background: #5469d4;
      border-radius: 0 10.2px 10.2px 0;
      top: -0.1px;
      left: 10.2px;
      -webkit-transform-origin: 0px 10.2px;
      transform-origin: 0px 10.2px;
      -webkit-animation: loading 2s infinite ease;
      animation: loading 2s infinite ease;
    }

    @-webkit-keyframes loading {
      0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
      }

      100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }

    @keyframes loading {
      0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
      }

      100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }

    @media only screen and (max-width: 600px) {
      form {
        width: 80vw;
        min-width: initial;
      }
    }

  </style>
  @endpush

  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div id="payment-message" class="hidden"></div>
          <form id="payment-form" action="{{route('invoice.store')}}" method="post">
            @csrf
            <input type="hidden" name="price_id" value="price_1OhvmeGz3EyK1lQ9oBXNYQZn" />
            <div id="payment-element">
              <!--Stripe.js injects the Payment Element-->
            </div>
            <button id="submit" class="align-middle select-none font-sans font-bold text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 px-6 rounded-lg bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none mt-3">
              <div class="spinner hidden" id="spinner"></div>
              <span id="button-text">Pay now</span>
            </button>

          </form>
        </div>
      </div>
    </div>
  </div>
  @push('scripts')
  <script src="https://js.stripe.com/v3/"></script>

  <script>
    const stripe = Stripe("pk_test_51HG9eAGz3EyK1lQ9ld9Ora0Q4VCtiLIjVvCP3Y9mgGij4bjHfluVV4JaVZWHavWYiU2LMDo02vcJn3J3qUFTLiOL00zPcHpyiB");

    let elements;

    initialize();
    checkStatus();

    document
      .querySelector("#payment-form")
      .addEventListener("submit", handleSubmit);

    function initialize() {

      elements = stripe.elements({
        clientSecret: "{{ $intent->client_secret }}"
        , appearance: {
          theme: "auto"
        , }
      , });

      const paymentElementOptions = {
        layout: "tabs"
      , };

      const paymentElement = elements.create("payment", paymentElementOptions);
      paymentElement.mount("#payment-element");
    }

    async function handleSubmit(e) {
      e.preventDefault();
      setLoading(true);

      const {
        setupIntent
        , error
      } = await stripe.confirmSetup({
        elements
        , confirmParams: {
          // Make sure to change this to your payment completion page
          return_url: "http://localhost:4242/checkout.html"
        , }
        , redirect: "if_required"
      , });

      if (error) {
        if (error.type === "card_error" || error.type === "validation_error") {
          showMessage(error.message);
        } else {
          if (error.message) {
            showMessage(error.message);
          } else {
            showMessage("An unexpected error occurred.");
          }
        }
      } else {
        const form = document.getElementById('payment-form');
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'payment_method');
        hiddenInput.setAttribute('value', setupIntent.payment_method);
        form.appendChild(hiddenInput);
        HTMLFormElement.prototype.submit.call(form);
      }

      setLoading(false);
    }

    async function checkStatus() {
      const clientSecret = new URLSearchParams(window.location.search).get(
        "payment_intent_client_secret"
      );

      if (!clientSecret) {
        return;
      }

      const {
        paymentIntent
      } = await stripe.retrievePaymentIntent(clientSecret);

      switch (paymentIntent.status) {
        case "succeeded":
          showMessage("Payment succeeded!");
          break;
        case "processing":
          showMessage("Your payment is processing.");
          break;
        case "requires_payment_method":
          showMessage("Your payment was not successful, please try again.");
          break;
        default:
          showMessage("Something went wrong.");
          break;
      }
    }

    // ------- UI helpers -------
    function showMessage(messageText) {
      const messageContainer = document.querySelector("#payment-message");
      messageContainer.style.color = "red";
      messageContainer.style.fontSize = "18px";

      messageContainer.classList.remove("hidden");
      messageContainer.textContent = messageText;

      setTimeout(function() {
        messageContainer.classList.add("hidden");
        messageContainer.textContent = "";
      }, 10000);
    }

    // Show a spinner on payment submission
    function setLoading(isLoading) {
      if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("#submit").disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#button-text").classList.add("hidden");
      } else {
        document.querySelector("#submit").disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#button-text").classList.remove("hidden");
      }
    }

  </script>
  @endpush
</x-app-layout>
