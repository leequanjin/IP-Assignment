<?php
require_once 'controllers/CartController.php';
require_once 'decorator/BasePrice.php';
require_once 'decorator/DiscountDecorator.php';
require_once 'apis/CurrencyConverter.php'; // If not already included
?>

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th scope="col" class="text-center">Product Title</th>
            <th scope="col" class="text-center">Product Image</th>
            <th scope="col" class="text-center">Quantity</th>
            <th scope="col" class="text-center">Product Price</th>
            <th scope="col" class="text-center">Total Price</th>
            <th scope="col" class="text-center">Remove</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $grandTotal = 0;
        $conversionRate = 1;
        $currencyConverter = new CurrencyConverter();
        $selectedCurrency = $_GET['currency'] ?? 'MYR';
        if ($selectedCurrency !== 'MYR') {
            $conversionRate = $currencyConverter->getConversionRate($selectedCurrency);
        }

        // Handle voucher discount
        $voucher = $_GET['voucher'] ?? null;
        $validVouchers = ['DISCOUNT10' => 0.10, 'DISCOUNT20' => 0.20, 'DISCOUNT50' => 0.50];
        $discountRate = isset($validVouchers[$voucher]) ? $validVouchers[$voucher] : 0;
        ?>

        <?php foreach ($cartProductDetails as $product): ?>
            <?php
            $id = $product['id'];
            $title = $product['title'];
            $image = $product['image'];
            $imageSrc = 'uploads/' . $image;
            $qty = (int) $product['qty'];
            $price = (float) $product['price'];

            $convertedPrice = $price * $conversionRate;

            // Apply base and discount decorators
            $calculator = new BasePrice();
            if ($discountRate > 0) {
                $calculator = new DiscountDecorator($calculator, $discountRate);
            }

            $subTotal = $calculator->calculatePrice($convertedPrice, $qty);
            $grandTotal += $subTotal;
            ?>
            <tr>
                <td class="text-center"><?php echo htmlspecialchars($title); ?></td>
                <td class="text-center"><img src="<?php echo $imageSrc; ?>" width="100" height="100"></td>
                <td class="text-center">
                    <form action="userIndex.php?module=cart&action=update&currency=<?php echo urlencode($selectedCurrency); ?>" method="POST" class="d-flex justify-content-center align-items-center gap-2">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($id); ?>">
                        <input type="number" name="quantity" value="<?php echo htmlspecialchars($qty); ?>" min="1" class="form-control" style="width: 70px;">
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>

                <td class="text-center"><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($convertedPrice, 2); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($subTotal, 2); ?></td>
                <td class="text-center">
                    <a href="userIndex.php?module=cart&action=delete&product_id=<?php echo urlencode($id); ?>&currency=<?php echo urlencode($selectedCurrency); ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Remove <?php echo $title ?> from cart?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>

        <!-- Voucher Form -->
        <tr>
            <td colspan="6">
                <form method="GET" class="d-flex justify-content-center align-items-center gap-2 mb-3">
                    <input type="hidden" name="module" value="cart">
                    <input type="hidden" name="action" value="view">
                    <input type="hidden" name="currency" value="<?php echo htmlspecialchars($selectedCurrency); ?>">
                    <input type="text" name="voucher" class="form-control w-25" placeholder="Enter Voucher Code" value="<?php echo isset($_GET['voucher']) ? htmlspecialchars($_GET['voucher']) : ''; ?>">
                    <button type="submit" class="btn btn-outline-success">Apply Voucher</button>
                </form>
            </td>
        </tr>

        <?php if ($discountRate > 0): ?>
            <tr>
                <td colspan="6">
                    <div class="alert alert-success text-center fw-bold">
                        🎉 <?php echo $discountRate * 100; ?>% Discount Applied with Voucher: <?php echo htmlspecialchars($voucher); ?>
                    </div>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <td colspan="4" class="text-end fw-bold">Grand Total:</td>
            <td class="text-center fw-bold"><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($grandTotal, 2); ?></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" class="text-end">
                <a href="userIndex.php" class="btn btn-secondary">Continue Shopping</a>
                <form action="apis/stripe_checkout.php" method="POST" class="d-inline">
                    <input type="hidden" name="currency" value="<?php echo htmlspecialchars($selectedCurrency); ?>">
                    <input type="hidden" name="grand_total" value="<?php echo htmlspecialchars($grandTotal); ?>">
                    <button type="submit" class="btn btn-success">Proceed to Payment</button>
                </form>
            </td>
        </tr>
    </tbody>
</table>
