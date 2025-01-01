<!DOCTYPE html>
<html>
<head>
    <title>Payment Gateway Integration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <h1 class="text-center">Payment</h1>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title text-center">Checkout Form</h2>
                </div>
                <div class="panel-body">
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
                    <form id='checkout-form' method='post' action="{{ route('stripe.post') }}">
                        @csrf
                        <input type='hidden' name='job_id' value="{{ $job_id }}">
                        <input type='hidden' name='technician_id' value="{{ $technician_id }}">
                        <input type='hidden' name='amount' value="{{ $amount }}">
                        <input type='hidden' name='stripeToken' id='stripe-token-id'>
                        <div id="card-element" class="form-control"></div>
                        <button id='pay-btn' class="btn btn-success mt-3" type="button" style="margin-top: 20px; width: 100%;">
                            PAY ${{ $amount }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    function createToken() {
        document.getElementById("pay-btn").disabled = true;
        stripe.createToken(cardElement).then(function(result) {
            if (typeof result.error != 'undefined') {
                document.getElementById("pay-btn").disabled = false;
                alert(result.error.message);
            }
            if (typeof result.token != 'undefined') {
                document.getElementById("stripe-token-id").value = result.token.id;
                document.getElementById('checkout-form').submit();
            }
        });
    }

    document.getElementById('pay-btn').addEventListener('click', createToken);
</script>
</body>
</html>