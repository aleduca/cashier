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