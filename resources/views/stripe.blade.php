<!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h1>Stripe Payment</h1>
    <form id="payment-form">
        <div id="card-element">
            <!-- Stripe Card Element will be inserted here -->
        </div>
        <button id="submit-button">Pay Now</button>
    </form>

    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const { error, paymentMethod } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                alert(error.message);
            } else {
                // Send paymentMethod.id to your server
                fetch('/process-stripe-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_method_id: paymentMethod.id,
                        bid_id: '{{ $bid->id }}'
                    })
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          window.location.href = data.redirect_url;
                      } else {
                          alert('Payment failed.');
                      }
                  });
            }
        });
    </script>
</body>
</html>