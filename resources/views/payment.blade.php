<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Paypal</title>
</head>
<body>
<h2>product: bag</h2>
<h3>price:8jd</h3>

<form action="{{route('payment')}}" method="post">
@csrf
<input type="hidden" name="price" value="5">
<input type="hidden" name="product_name" value="bag">
<input type="hidden" name="quantity" value="1">
<button type="submit">Pay with Paypal</button>
</form>
</body>
</html>
