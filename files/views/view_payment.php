<?php
require_once 'models/CartModel.php';
require_once 'models/ProductModel.php';
require_once 'apis/CurrencyConverter.php';

$cartModel = new CartModel();
$productModel = new ProductModel();
$currencyConverter = new CurrencyConverter();

$user_id = 1; // Replace with session user ID when available
// Currency setup
$selectedCurrency = $_GET['currency'] ?? 'MYR';
$conversionRate = 1;
if ($selectedCurrency !== 'MYR') {
    $conversionRate = $currencyConverter->getConversionRate($selectedCurrency);
}

// Get cart data
$cartProducts = $cartModel->getUserCart($user_id);
$cartProductDetails = [];
$grandTotal = 0.0;

foreach ($cartProducts as $productId => $qty) {
    $productData = $productModel->getProductData($productId);

    if (!empty($productData['title'])) {
        $price = floatval($productData['price']) * $conversionRate;
        $subtotal = $price * $qty;
        $grandTotal += $subtotal;

        $cartProductDetails[] = [
            'title' => $productData['title'],
            'image' => $productData['image'],
            'qty' => $qty,
            'price' => $price,
            'subtotal' => $subtotal
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Review Payment</title>
        <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Adjust path -->
        <script src="https://js.stripe.com/v3/"></script> <!-- Stripe JavaScript -->
    </head>
    <body>
        <div class="container mt-5">
            <h2 class="mb-4">Review Your Order</h2>

            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th class="text-center">Product Title</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Price (<?php echo htmlspecialchars($selectedCurrency); ?>)</th>
                        <th class="text-center">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartProductDetails as $product): ?>
                        <tr>
                            <td class="text-center"><?php echo htmlspecialchars($product['title']); ?></td>
                            <td class="text-center">
                                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" width="100" height="100">
                            </td>
                            <td class="text-center"><?php echo htmlspecialchars($product['qty']); ?></td>
                            <td class="text-center"><?php echo number_format($product['price'], 2); ?></td>
                            <td class="text-center"><?php echo number_format($product['subtotal'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                        <td class="text-center fw-bold"><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($grandTotal, 2); ?></td>
                    </tr>
                </tbody>
            </table>

            <form method="POST" action="userIndex.php?module=payment&action=process_payment&currency=<?php echo urlencode($selectedCurrency); ?>">
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Select Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="">-- Select --</option>
                        <option value="cod">Cash on Delivery</option>
                        <option value="stripe">Stripe</option> <!-- Add Stripe -->
                    </select>
                </div>

                <!-- Stripe Form -->
                <div id="stripe_form" style="display: none;">
                    <label for="card-element">Credit or Debit Card</label>
                    <div id="card-element">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                    <div id="card-errors" role="alert"></div>
                </div>

                <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($grandTotal); ?>">

                <div class="mb-3">
                    <p><strong>Total: </strong><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($grandTotal, 2); ?></p>
                </div>

                <button type="submit" class="btn btn-success">Proceed to Pay</button>
            </form>
        </div>

        <script>
            // Handle Payment Method Selection
            const paymentMethodSelect = document.getElementById('payment_method');
            const stripeForm = document.getElementById('stripe_form');

            paymentMethodSelect.addEventListener('change', function () {
                if (this.value === 'stripe') {
                    stripeForm.style.display = 'block'; // Show Stripe form
                } else {
                    stripeForm.style.display = 'none'; // Hide Stripe form
                }
            });

            // Stripe Setup
            var stripe = Stripe('pk_test_51RKkBoRsAxI4hcgOSOM4ojRLk5IE7XcmuvOxbkWmou1XJk3MJvbg1yq704n3tSvefqrCT58Ox7BbxnDOhPlrkfIW00Wl0nSs8O'); // Your public key
            var elements = stripe.elements();
            var card = elements.create('card');
            card.mount('#card-element');

            // Form Submission Handling
            const form = document.querySelector('form');
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                if (paymentMethodSelect.value === 'stripe') {
                    stripe.createToken(card).then(function (result) {
                        if (result.error) {
                            // Show error in the UI
                            document.getElementById('card-errors').textContent = result.error.message;
                        } else {
                            // Append the token to the form and submit it
                            var tokenInput = document.createElement('input');
                            tokenInput.type = 'hidden';
                            tokenInput.name = 'stripeToken';
                            tokenInput.value = result.token.id;
                            form.appendChild(tokenInput);
                            form.submit();
                        }
                    });
                } else {
                    form.submit();
                }
            });
        </script>

         </body>
</html>

